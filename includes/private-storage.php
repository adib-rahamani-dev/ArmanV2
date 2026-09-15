<?php
declare(strict_types=1);

function upload_error_message(int $code): string
{
    return match ($code) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'حجم فایل بیشتر از حد مجاز است.',
        UPLOAD_ERR_PARTIAL => 'فایل کامل دریافت نشد؛ دوباره تلاش کنید.',
        UPLOAD_ERR_NO_FILE => 'فایل موردنیاز انتخاب نشده است.',
        default => 'آپلود فایل انجام نشد.',
    };
}

function validate_private_image(array $file, string $label): array
{
    $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error !== UPLOAD_ERR_OK) throw new InvalidArgumentException($label . ': ' . upload_error_message($error));
    $size = (int) ($file['size'] ?? 0);
    if ($size < 1024 || $size > 5 * 1024 * 1024) throw new InvalidArgumentException($label . ': حجم تصویر باید بین ۱ کیلوبایت تا ۵ مگابایت باشد.');
    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) throw new InvalidArgumentException($label . ': فایل معتبر نیست.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp) ?: '';
    $allowed = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
    if (!isset($allowed[$mime])) throw new InvalidArgumentException($label . ': فقط JPG، PNG یا WebP پذیرفته می‌شود.');
    $dimensions = @getimagesize($tmp);
    if (!is_array($dimensions) || ($dimensions[0] ?? 0) < 320 || ($dimensions[1] ?? 0) < 200) {
        throw new InvalidArgumentException($label . ': کیفیت تصویر برای بررسی کافی نیست.');
    }
    return ['tmp'=>$tmp, 'mime'=>$mime, 'ext'=>$allowed[$mime], 'size'=>$size];
}

function private_blob_upload(array $validated, string $orderId, string $kind): array
{
    $safeOrder = preg_replace('/[^A-Za-z0-9-]/', '', $orderId);
    $pathname = 'verification/' . $safeOrder . '/' . $kind . '-' . bin2hex(random_bytes(12)) . '.' . $validated['ext'];
    $token = trim((string) (getenv('BLOB_READ_WRITE_TOKEN') ?: ''));
    if ($token !== '') {
        $endpoint = 'https://vercel.com/api/blob/?pathname=' . rawurlencode($pathname);
        $handle = curl_init($endpoint);
        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => file_get_contents($validated['tmp']),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'x-api-version: 12',
                'x-content-type: ' . $validated['mime'],
                'x-cache-control-max-age: 60',
            ],
        ]);
        $body = curl_exec($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $error = curl_error($handle);
        curl_close($handle);
        $result = is_string($body) ? json_decode($body, true) : null;
        if ($status < 200 || $status >= 300 || !is_array($result) || empty($result['url'])) {
            throw new RuntimeException('ذخیره امن مدرک انجام نشد.' . ($error !== '' ? ' ' . $error : ''));
        }
        return ['provider'=>'vercel_blob', 'url'=>(string) $result['url'], 'pathname'=>(string) ($result['pathname'] ?? $pathname), 'mime'=>$validated['mime']];
    }

    if (APP_ENV === 'production') throw new RuntimeException('فضای ذخیره‌سازی خصوصی متصل نیست.');
    $directory = STORAGE_PATH . '/private/' . $safeOrder;
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) throw new RuntimeException('فضای امن محلی ساخته نشد.');
    $target = $directory . '/' . basename($pathname);
    if (!move_uploaded_file($validated['tmp'], $target)) throw new RuntimeException('ذخیره فایل انجام نشد.');
    return ['provider'=>'local', 'url'=>'', 'pathname'=>$target, 'mime'=>$validated['mime']];
}

function private_blob_read(string $provider, string $location): array
{
    if ($provider === 'local') {
        $base = realpath(STORAGE_PATH . '/private');
        $path = realpath($location);
        if (!$base || !$path || !str_starts_with($path, $base . DIRECTORY_SEPARATOR) || !is_file($path)) throw new RuntimeException('فایل پیدا نشد.');
        return ['body'=>(string) file_get_contents($path), 'mime'=>(new finfo(FILEINFO_MIME_TYPE))->file($path) ?: 'application/octet-stream'];
    }
    $token = trim((string) (getenv('BLOB_READ_WRITE_TOKEN') ?: ''));
    if ($token === '' || !str_starts_with($location, 'https://')) throw new RuntimeException('دسترسی فایل ممکن نیست.');
    $handle = curl_init($location);
    curl_setopt_array($handle, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>30, CURLOPT_HTTPHEADER=>['Authorization: Bearer ' . $token]]);
    $body = curl_exec($handle);
    $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
    $mime = (string) curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
    curl_close($handle);
    if ($status !== 200 || !is_string($body)) throw new RuntimeException('دریافت فایل ممکن نیست.');
    return ['body'=>$body, 'mime'=>$mime ?: 'application/octet-stream'];
}

function encrypt_sensitive(string $plain): string
{
    $encoded = trim((string) (getenv('APP_ENCRYPTION_KEY') ?: ''));
    $key = base64_decode($encoded, true);
    if (!is_string($key) || strlen($key) !== 32) {
        if (APP_ENV === 'production') throw new RuntimeException('کلید رمزنگاری تنظیم نشده است.');
        $key = hash('sha256', __FILE__ . php_uname(), true);
    }
    $iv = random_bytes(12);
    $tag = '';
    $ciphertext = openssl_encrypt($plain, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    if (!is_string($ciphertext)) throw new RuntimeException('رمزنگاری اطلاعات انجام نشد.');
    return base64_encode($iv . $tag . $ciphertext);
}
