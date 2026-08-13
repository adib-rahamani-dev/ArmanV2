<?php
declare(strict_types=1);
const APP_NAME = 'REDT';
if (!defined('APP_URL')) {
    $configuredPath = getenv('APP_PATH');
    define('APP_URL', $configuredPath === false ? '/ArmanV2' : '/' . trim((string) $configuredPath, '/'));
}
const SITE_NAME_FA = 'گروه قرمز';
const SITE_DESCRIPTION = 'آژانس برندینگ، طراحی سایت و تولید محتوای REDT؛ از استراتژی و هویت بصری تا طراحی تجربه دیجیتال و رشد کسب‌وکار.';
const SITE_LOCALE = 'fa_IR';
const STORAGE_PATH = __DIR__ . '/../storage';
if (!defined('APP_ENV')) {
    define('APP_ENV', getenv('APP_ENV') ?: 'development');
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS'])]);
    session_start();
}
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo.php';
