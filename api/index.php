<?php
declare(strict_types=1);

$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$path = '/' . trim($path, '/');
$root = dirname(__DIR__);

if (PHP_SAPI === 'cli-server') {
    $staticPath = realpath($root . str_replace('/', DIRECTORY_SEPARATOR, $path));
    if ($staticPath !== false && is_file($staticPath) && str_starts_with($staticPath, $root . DIRECTORY_SEPARATOR)) return false;
}

$target = match ($path) {
    '/', '/index.php' => $root . '/index.php',
    '/studio', '/studio/index.php' => $root . '/studio/index.php',
    '/digital', '/digital/index.php' => $root . '/digital/index.php',
    '/arman', '/arman/index.php' => $root . '/arman/index.php',
    '/user', '/user/index.php' => $root . '/user/index.php',
    '/admin', '/admin/index.php' => $root . '/admin/index.php',
    '/admin/api.php' => $root . '/admin/api.php',
    '/admin/document.php' => $root . '/admin/document.php',
    '/api/order.php' => $root . '/api/order.php',
    '/api/consultation.php' => $root . '/api/consultation.php',
    '/api/purchase.php' => $root . '/api/purchase.php',
    '/api/verification.php' => $root . '/api/verification.php',
    '/api/track-order.php' => $root . '/api/track-order.php',
    '/verify.php' => $root . '/verify.php',
    '/privacy.php' => $root . '/privacy.php',
    '/privacy' => $root . '/privacy.php',
    '/terms.php' => $root . '/terms.php',
    '/terms' => $root . '/terms.php',
    '/robots.txt', '/robots.php' => $root . '/robots.php',
    '/sitemap.xml', '/sitemap.php' => $root . '/sitemap.php',
    '/404', '/404.php' => $root . '/404.php',
    '/page.php' => $root . '/page.php',
    default => null,
};

if ($target === null || !is_file($target)) {
    require $root . '/404.php';
    exit;
}

require $target;
