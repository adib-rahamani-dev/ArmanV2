<?php
declare(strict_types=1);
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string {
    if (preg_match('~^https?://~i', $path) === 1) return $path;
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}
function surface_url(string $surface = 'main', string $path = ''): string {
    $envKey = match ($surface) {
        'digital' => 'DIGITAL_BASE_URL',
        'studio' => 'STUDIO_BASE_URL',
        'arman' => 'ARMAN_BASE_URL',
        default => 'APP_BASE_URL',
    };
    $configured = trim((string) (getenv($envKey) ?: ''));
    if ($configured !== '') return rtrim($configured, '/') . ($path !== '' ? '/' . ltrim($path, '/') : '/');
    $prefix = match ($surface) {
        'digital' => 'digital/',
        'studio' => 'studio/',
        'arman' => 'arman/',
        default => '',
    };
    return url($prefix . ltrim($path, '/'));
}
function is_https(): bool {
    if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') return true;
    return strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
}
function site_base_url(): string {
    $configured = trim((string) (getenv('APP_BASE_URL') ?: ''));
    if ($configured !== '') return rtrim($configured, '/');
    $host = preg_replace('/[^a-zA-Z0-9.\-:\[\]]/', '', (string) ($_SERVER['HTTP_HOST'] ?? 'localhost')) ?: 'localhost';
    return (is_https() ? 'https' : 'http') . '://' . $host . rtrim(APP_URL, '/');
}
function absolute_url(string $path = ''): string {
    if (preg_match('~^https?://~i', $path) === 1) return $path;
    $clean = ltrim($path, '/');
    return site_base_url() . ($clean !== '' ? '/' . $clean : '/');
}
function current_canonical_url(array $allowedQueryKeys = ['type', 'id']): string {
    $requestPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $appPrefix = rtrim(APP_URL, '/');
    if ($appPrefix !== '' && str_starts_with($requestPath, $appPrefix)) {
        $requestPath = substr($requestPath, strlen($appPrefix)) ?: '/';
    }
    $query = [];
    foreach ($allowedQueryKeys as $key) {
        if (isset($_GET[$key]) && is_scalar($_GET[$key])) $query[$key] = trim((string) $_GET[$key]);
    }
    $canonical = absolute_url(ltrim($requestPath, '/'));
    return $query === [] ? $canonical : $canonical . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
}
function asset_url(string $path): string {
    $relative = ltrim($path, '/');
    $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
    $version = is_file($file) ? (string) filemtime($file) : '1';
    return url($relative) . '?v=' . rawurlencode($version);
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $cookieToken = (string) ($_COOKIE['redt_csrf'] ?? '');
        $_SESSION['csrf'] = preg_match('/^[a-f0-9]{64}$/', $cookieToken) === 1 ? $cookieToken : bin2hex(random_bytes(32));
    }
    if (!headers_sent() && !hash_equals((string) ($_COOKIE['redt_csrf'] ?? ''), (string) $_SESSION['csrf'])) {
        setcookie('redt_csrf', (string) $_SESSION['csrf'], [
            'expires'=>time() + 7200,
            'path'=>'/',
            'secure'=>is_https(),
            'httponly'=>true,
            'samesite'=>'Lax',
        ]);
    }
    return (string) $_SESSION['csrf'];
}
function csrf_valid(?string $token): bool {
    $expected = (string) ($_SESSION['csrf'] ?? $_COOKIE['redt_csrf'] ?? '');
    return is_string($token) && $expected !== '' && hash_equals($expected, $token);
}
function is_post(): bool { return ($_SERVER['REQUEST_METHOD']??'GET')==='POST'; }
function json_response(array $data,int $status=200): never { http_response_code($status); header('Content-Type: application/json; charset=utf-8'); echo json_encode($data,JSON_UNESCAPED_UNICODE); exit; }
function track_page_view(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET' || headers_sent()) return;
    $agent = strtolower((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));
    if ($agent === '' || preg_match('/bot|crawl|spider|slurp|preview|monitor/', $agent)) return;
    $dir = STORAGE_PATH . '/analytics';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) return;
    $file = $dir . '/pageviews.json';
    $handle = @fopen($file, 'c+');
    if (!$handle || !flock($handle, LOCK_EX)) { if ($handle) fclose($handle); return; }
    $raw = stream_get_contents($handle);
    $data = is_string($raw) && $raw !== '' ? json_decode($raw, true) : [];
    if (!is_array($data)) $data = [];
    $today = date('Y-m-d');
    $requestPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $query = [];
    foreach (['type','id'] as $key) if (isset($_GET[$key]) && is_scalar($_GET[$key])) $query[$key] = substr(trim((string) $_GET[$key]), 0, 80);
    $route = $requestPath . ($query ? '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986) : '');
    $sessionKey = substr(hash('sha256', session_id() . $today), 0, 20);
    $device = preg_match('/mobile|android|iphone|ipad/', $agent) ? 'mobile' : 'desktop';
    $day = $data['days'][$today] ?? ['views'=>0,'sessions'=>[],'paths'=>[],'devices'=>['desktop'=>0,'mobile'=>0]];
    $day['views'] = (int) ($day['views'] ?? 0) + 1;
    $day['sessions'][$sessionKey] = true;
    $day['paths'][$route] = (int) ($day['paths'][$route] ?? 0) + 1;
    $day['devices'][$device] = (int) ($day['devices'][$device] ?? 0) + 1;
    $data['days'][$today] = $day;
    $cutoff = date('Y-m-d', strtotime('-90 days'));
    foreach (($data['days'] ?? []) as $date => $_) if ($date < $cutoff) unset($data['days'][$date]);
    rewind($handle); ftruncate($handle, 0);
    fwrite($handle, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    fflush($handle); flock($handle, LOCK_UN); fclose($handle);
}
function analytics_summary(int $period = 7): array {
    $file = STORAGE_PATH . '/analytics/pageviews.json';
    $data = ['days'=>[]];
    if (is_file($file) && ($handle = @fopen($file, 'r'))) {
        if (flock($handle, LOCK_SH)) { $raw = stream_get_contents($handle); $decoded = json_decode((string) $raw, true); if (is_array($decoded)) $data = $decoded; flock($handle, LOCK_UN); }
        fclose($handle);
    }
    $series=[]; $total=0; $unique=0; $paths=[]; $devices=['desktop'=>0,'mobile'=>0];
    for ($i=$period-1;$i>=0;$i--) {
        $date=date('Y-m-d',strtotime("-$i days")); $day=$data['days'][$date]??[]; $views=(int)($day['views']??0);
        $series[]=['date'=>$date,'label'=>date('m/d',strtotime($date)),'views'=>$views]; $total+=$views; $unique+=count($day['sessions']??[]);
        foreach(($day['paths']??[]) as $path=>$count)$paths[$path]=(int)($paths[$path]??0)+(int)$count;
        foreach($devices as $kind=>$_)$devices[$kind]+=(int)($day['devices'][$kind]??0);
    }
    arsort($paths); $today=$data['days'][date('Y-m-d')]??[]; $yesterday=$data['days'][date('Y-m-d',strtotime('-1 day'))]??[];
    return ['total'=>$total,'unique'=>$unique,'today'=>(int)($today['views']??0),'yesterday'=>(int)($yesterday['views']??0),'series'=>$series,'paths'=>array_slice($paths,0,8,true),'devices'=>$devices];
}

function storage_json(string $relative, array $fallback = []): array {
    $path = STORAGE_PATH . '/' . ltrim($relative, '/');
    if (!is_file($path)) return $fallback;
    $handle = @fopen($path, 'r');
    if (!$handle) return $fallback;
    $raw = '';
    if (flock($handle, LOCK_SH)) {
        $raw = (string) stream_get_contents($handle);
        flock($handle, LOCK_UN);
    }
    fclose($handle);
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : $fallback;
}

function storage_json_write(string $relative, array $data): bool {
    $path = STORAGE_PATH . '/' . ltrim($relative, '/');
    $directory = dirname($path);
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) return false;
    $handle = @fopen($path, 'c+');
    if (!$handle || !flock($handle, LOCK_EX)) { if ($handle) fclose($handle); return false; }
    $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if (!is_string($payload)) { flock($handle, LOCK_UN); fclose($handle); return false; }
    rewind($handle);
    ftruncate($handle, 0);
    $written = fwrite($handle, $payload) !== false;
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);
    return $written;
}

function read_json_lines(string $relative): array {
    $path = STORAGE_PATH . '/' . ltrim($relative, '/');
    if (!is_file($path)) return [];
    $rows = [];
    $handle = @fopen($path, 'r');
    if (!$handle) return [];
    if (flock($handle, LOCK_SH)) {
        while (($line = fgets($handle)) !== false) {
            $row = json_decode(trim($line), true);
            if (!is_array($row)) continue;
            $row['_key'] = substr(hash('sha256', $relative . '|' . $line), 0, 16);
            $rows[] = $row;
        }
        flock($handle, LOCK_UN);
    }
    fclose($handle);
    return array_reverse($rows);
}

function apply_catalog_overrides(array $catalog): array {
    $overrides = storage_json('catalog-overrides.json', []);
    try {
        $statement = db()->query('SELECT group_name, product_id, active FROM product_overrides');
        foreach ($statement->fetchAll() as $row) $overrides[$row['group_name'] . ':' . $row['product_id']] = (bool) $row['active'];
    } catch (Throwable) {
        // The static catalog remains usable while a production database is being connected.
    }
    foreach ($catalog as $group => &$items) {
        if (!is_array($items)) continue;
        foreach ($items as &$item) {
            if (!is_array($item) || empty($item['id'])) continue;
            $key = $group . ':' . $item['id'];
            if (array_key_exists($key, $overrides)) $item['active'] = (bool) $overrides[$key];
        }
        unset($item);
    }
    unset($items);
    return $catalog;
}

function admin_is_authenticated(): bool {
    if (!empty($_SESSION['admin_authenticated']) && is_int($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] > time() - 43200) return true;
    $cookie = (string) ($_COOKIE['redt_admin'] ?? '');
    if ($cookie === '' || !str_contains($cookie, '.')) return false;
    [$payload, $signature] = explode('.', $cookie, 2);
    $secret = (string) (getenv('AUTH_SECRET') ?: getenv('ADMIN_PASSWORD') ?: '');
    if ($secret === '' || !hash_equals(hash_hmac('sha256', $payload, $secret), $signature)) return false;
    $decoded = json_decode((string) base64_decode(strtr($payload, '-_', '+/'), true), true);
    return is_array($decoded) && ($decoded['exp'] ?? 0) > time() && hash_equals((string) (getenv('ADMIN_USERNAME') ?: 'admin'), (string) ($decoded['sub'] ?? ''));
}

function admin_sign_in(string $username): void {
    $_SESSION['admin_authenticated'] = time();
    $payload = rtrim(strtr(base64_encode(json_encode(['sub'=>$username, 'exp'=>time() + 43200], JSON_UNESCAPED_SLASHES)), '+/', '-_'), '=');
    $secret = (string) (getenv('AUTH_SECRET') ?: getenv('ADMIN_PASSWORD') ?: '');
    if ($secret !== '' && !headers_sent()) setcookie('redt_admin', $payload . '.' . hash_hmac('sha256', $payload, $secret), ['expires'=>time()+43200, 'path'=>'/', 'secure'=>is_https(), 'httponly'=>true, 'samesite'=>'Strict']);
}

function admin_sign_out(): void {
    unset($_SESSION['admin_authenticated']);
    if (!headers_sent()) setcookie('redt_admin', '', ['expires'=>time()-3600, 'path'=>'/', 'secure'=>is_https(), 'httponly'=>true, 'samesite'=>'Strict']);
}

function admin_credentials_valid(string $username, string $password): bool {
    $expectedUser = (string) (getenv('ADMIN_USERNAME') ?: 'admin');
    $configured = (string) (getenv('ADMIN_PASSWORD') ?: '');
    if ($configured === '' && APP_ENV !== 'production') $configured = 'redt-admin';
    if ($configured === '' || !hash_equals($expectedUser, $username)) return false;
    return str_starts_with($configured, '$2y$') || str_starts_with($configured, '$argon2')
        ? password_verify($password, $configured)
        : hash_equals($configured, $password);
}

function admin_order_rows(): array {
    try {
        $sql = "SELECT o.id AS _key, o.tracking_code AS order_id, o.source AS _source, o.product_title AS _title,
                       o.price_snapshot AS _amount, o.status AS _status, o.payment_status, o.identity_status,
                       o.created_at AS date, c.full_name AS name, c.phone, c.email,
                       v.id AS verification_id, v.status AS verification_status
                FROM orders o JOIN customers c ON c.id=o.customer_id
                LEFT JOIN verifications v ON v.order_id=o.id ORDER BY o.created_at DESC";
        $rows = db()->query($sql)->fetchAll();
        if (is_array($rows)) return $rows;
    } catch (Throwable) {
        // Keep legacy local records visible if database setup is incomplete.
    }
    $states = storage_json('admin/order-status.json', []);
    $rows = [];
    foreach (read_json_lines('messages/orders.log') as $row) {
        $row['_source'] = 'service';
        $row['_title'] = (string) ($row['service'] ?? 'سفارش خدمات');
        $row['_amount'] = (string) ($row['budget'] ?? 'نیازمند بررسی');
        $row['_status'] = (string) ($states[$row['_key']] ?? 'new');
        $rows[] = $row;
    }
    foreach (read_json_lines('messages/purchases.log') as $row) {
        $row['_source'] = 'purchase';
        $row['_title'] = (string) ($row['product_title'] ?? 'خرید محصول');
        $row['_amount'] = (string) ($row['price'] ?? 'نیازمند بررسی');
        $row['_status'] = (string) ($states[$row['_key']] ?? 'new');
        $rows[] = $row;
    }
    usort($rows, static fn(array $a, array $b): int => strcmp((string) ($b['date'] ?? ''), (string) ($a['date'] ?? '')));
    return $rows;
}

function fa_number(int|float $value): string {
    return strtr(number_format($value), ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹',','=>'٬']);
}
