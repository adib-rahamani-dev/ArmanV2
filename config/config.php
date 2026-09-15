<?php
declare(strict_types=1);
const APP_NAME = 'REDT';
if (!defined('APP_URL')) {
    $configuredPath = getenv('APP_PATH');
    $defaultPath = getenv('VERCEL') === '1' ? '' : '/ArmanV2';
    define('APP_URL', $configuredPath === false ? $defaultPath : '/' . trim((string) $configuredPath, '/'));
}
const SITE_NAME_FA = 'گروه قرمز';
const SITE_DESCRIPTION = 'آژانس برندینگ، طراحی سایت و تولید محتوای REDT؛ از استراتژی و هویت بصری تا طراحی تجربه دیجیتال و رشد کسب‌وکار.';
const SITE_LOCALE = 'fa_IR';
date_default_timezone_set('Asia/Tehran');
define('STORAGE_PATH', getenv('VERCEL') === '1' ? sys_get_temp_dir() . '/redt-storage' : __DIR__ . '/../storage');
if (!defined('APP_ENV')) {
    define('APP_ENV', getenv('APP_ENV') ?: (getenv('VERCEL') === '1' ? 'production' : 'development'));
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionPath = STORAGE_PATH . '/sessions';
    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0755, true);
    }
    if (is_dir($sessionPath) && is_writable($sessionPath)) {
        session_save_path($sessionPath);
    }
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    $secureCookie = !empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off';
    session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>$secureCookie,'path'=>rtrim(APP_URL,'/').'/' ]);
    session_start();
}
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(self)');
    header('Cross-Origin-Opener-Policy: same-origin');
}
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/private-storage.php';
require_once __DIR__ . '/../includes/seo.php';
csrf_token();
