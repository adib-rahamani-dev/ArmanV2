<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';
$site = require __DIR__ . '/../data/site.php';
$seo = [
    'title' => 'پنل مدیریت | REDT',
    'description' => 'پنل مدیریت داخلی REDT',
    'robots' => 'noindex,nofollow,noarchive',
];
require __DIR__ . '/../includes/header.php';
?>
<main id="main" class="inner-page">
    <section class="inner-hero">
        <div class="container">
            <span class="eyebrow">پنل مدیریت</span>
            <h1>زیرساخت مدیریت آماده است.</h1>
            <p>احراز هویت و ماژول‌های مدیریت محتوا در این بخش قرار می‌گیرند. این مسیر از ایندکس موتورهای جست‌وجو خارج شده است.</p>
            <a class="btn btn--primary" href="<?= url() ?>">بازگشت به سایت</a>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
