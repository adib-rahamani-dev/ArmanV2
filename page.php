<?php
declare(strict_types=1);

require __DIR__ . '/config/config.php';
$site = require __DIR__ . '/data/site.php';
$routes = require __DIR__ . '/data/routes.php';
$labels = [
    'products' => 'محصولات دیجیتال',
    'about' => 'درباره گروه قرمز',
    'blog' => 'مجله REDT',
    'team' => 'تیم ما',
    'privacy' => 'قوانین و حریم خصوصی',
    'courses' => 'دوره‌های آموزشی',
    'course' => 'جزئیات دوره',
    'service' => 'جزئیات خدمت',
    'project' => 'مطالعه موردی پروژه',
];
$type = is_scalar($_GET['type'] ?? null) ? trim((string) $_GET['type']) : '';
$isKnown = isset($labels[$type], $routes[$type]);
$title = $isKnown ? $labels[$type] : 'صفحه یافت نشد';

if (!$isKnown) {
    http_response_code(404);
}

$route = $isKnown ? $routes[$type] : [
    'title' => 'صفحه یافت نشد | REDT',
    'description' => 'صفحه‌ای که به دنبال آن بودید پیدا نشد.',
];
$seo = [
    'title' => $route['title'],
    'description' => $route['description'],
    // These are scaffold pages. Switch to index,follow only after their real,
    // unique content is published and add the route to the sitemap.
    'robots' => 'noindex,follow',
    'breadcrumbs' => [
        ['name' => 'خانه', 'url' => ''],
        ['name' => $title, 'url' => $isKnown ? $routes[$type]['path'] : '404.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>
<main id="main" class="inner-page">
    <section class="inner-hero">
        <div class="container">
            <nav class="breadcrumbs" aria-label="مسیر صفحه">
                <ol>
                    <li><a href="<?= url() ?>">خانه</a></li>
                    <li aria-current="page"><?= e($title) ?></li>
                </ol>
            </nav>
            <span class="eyebrow">REDT / <?= e($title) ?></span>
            <h1><?= e($title) ?></h1>
            <?php if ($isKnown): ?>
                <p>ساختار فنی این صفحه آماده است و پس از انتشار محتوای کامل، عنوان، توضیحات، داده‌های ساختاریافته و آدرس آن به‌صورت اختصاصی در نتایج جست‌وجو ارائه می‌شود.</p>
            <?php else: ?>
                <p>این آدرس وجود ندارد یا جابه‌جا شده است. از صفحه اصلی مسیر درست را پیدا کنید.</p>
            <?php endif; ?>
            <a class="btn btn--primary" href="<?= url() ?>">بازگشت به خانه ←</a>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
