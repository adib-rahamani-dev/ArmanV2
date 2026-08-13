<?php
declare(strict_types=1);
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string { return rtrim(APP_URL, '/') . '/' . ltrim($path, '/'); }
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
function csrf_token(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_valid(?string $token): bool { return is_string($token)&&hash_equals($_SESSION['csrf']??'',$token); }
function is_post(): bool { return ($_SERVER['REQUEST_METHOD']??'GET')==='POST'; }
function json_response(array $data,int $status=200): never { http_response_code($status); header('Content-Type: application/json; charset=utf-8'); echo json_encode($data,JSON_UNESCAPED_UNICODE); exit; }
