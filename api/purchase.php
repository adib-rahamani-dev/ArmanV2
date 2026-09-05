<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';

if (!is_post()) json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'], 405);
if (!csrf_valid($_POST['csrf'] ?? null)) json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'], 419);
if (!empty($_POST['website'])) json_response(['ok'=>true,'message'=>'سفارش ثبت شد.']);

$last = (int) ($_SESSION['last_purchase'] ?? 0);
if (time() - $last < 15) json_response(['ok'=>false,'message'=>'لطفاً چند ثانیه بعد دوباره تلاش کنید.'], 429);

$productId = trim((string) ($_POST['product_id'] ?? ''));
$plan = mb_substr(trim((string) ($_POST['plan'] ?? '')), 0, 120);
$name = mb_substr(trim((string) ($_POST['name'] ?? '')), 0, 120);
$phone = preg_replace('/[^0-9+]/', '', (string) ($_POST['phone'] ?? ''));
$activationEmail = mb_substr(trim((string) ($_POST['account'] ?? '')), 0, 190);
$notes = mb_substr(trim((string) ($_POST['notes'] ?? '')), 0, 1200);

$catalog = require __DIR__ . '/../data/catalog.php';
$product = null;
foreach (['products','accounts','courses','ads'] as $group) {
    foreach (($catalog[$group] ?? []) as $candidate) {
        if (($candidate['id'] ?? '') === $productId && ($candidate['active'] ?? true)) {
            $product = $candidate;
            $product['_group'] = $group;
            break 2;
        }
    }
}

if (!$product) json_response(['ok'=>false,'message'=>'این محصول در حال حاضر قابل سفارش نیست.'], 404);
if (mb_strlen($name) < 3 || !preg_match('/^(?:\+98|0)?9\d{9}$/', $phone)) {
    json_response(['ok'=>false,'message'=>'نام و شماره موبایل معتبر وارد کنید.'], 422);
}
if ($product['_group'] === 'accounts' && $activationEmail !== '' && !filter_var($activationEmail, FILTER_VALIDATE_EMAIL)) {
    json_response(['ok'=>false,'message'=>'ایمیل حساب را درست وارد کنید یا این فیلد را خالی بگذارید.'], 422);
}

$orderId = 'RDT-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 8));
$record = [
    'order_id' => $orderId,
    'date' => date(DATE_ATOM),
    'product_id' => $productId,
    'product_title' => (string) $product['title'],
    'group' => $product['_group'],
    'plan' => $plan,
    'price' => (string) $product['price'],
    'name' => $name,
    'phone' => $phone,
    'account' => $activationEmail,
    'notes' => $notes,
];
$directory = STORAGE_PATH . '/messages';
if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
    json_response(['ok'=>false,'message'=>'امکان ثبت سفارش وجود ندارد؛ با پشتیبانی تماس بگیرید.'], 500);
}
$payload = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
if (file_put_contents($directory . '/purchases.log', $payload, FILE_APPEND | LOCK_EX) === false) {
    json_response(['ok'=>false,'message'=>'ثبت سفارش انجام نشد؛ دوباره تلاش کنید.'], 500);
}
$_SESSION['last_purchase'] = time();
json_response(['ok'=>true,'message'=>'سفارش شما با موفقیت ثبت شد.','order_id'=>$orderId]);
