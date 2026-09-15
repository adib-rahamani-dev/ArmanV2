<?php
declare(strict_types=1);
require_once __DIR__ . '/config/config.php';
http_response_code(404);
$surface='main';
$pageTitle='صفحه پیدا نشد | REDT';
$pageDescription='این نشانی در اکوسیستم REDT وجود ندارد.';
$pageRobots='noindex,follow';
require __DIR__ . '/includes/surface-header.php';
?>
<main id="start" class="not-found"><div class="surface-shell"><p class="ncc-index">ERROR / 404</p><h1>این مسیر به جایی نمی‌رسد.</h1><p>ممکن است نشانی تغییر کرده باشد. از صفحه اصلی مسیر درست را انتخاب کنید.</p><a class="ncc-button ncc-button--primary" href="<?=surface_url('main')?>">بازگشت به REDT <span aria-hidden="true">←</span></a></div></main>
<?php require __DIR__ . '/includes/surface-footer.php'; ?>
