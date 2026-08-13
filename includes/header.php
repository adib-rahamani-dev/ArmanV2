<?php
declare(strict_types=1);

$site = $site ?? require __DIR__ . '/../data/site.php';
$seoMeta = seo_meta($seo ?? (isset($pageTitle) ? ['title' => $pageTitle] : []));
$requestedScript = realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? ''));
$homeScript = realpath(dirname(__DIR__) . '/index.php');
$isHome = $requestedScript !== false && $requestedScript === $homeScript;
?>
<!doctype html>
<html lang="fa-IR" dir="rtl" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?= e($seoMeta['title']) ?></title>
    <meta name="description" content="<?= e($seoMeta['description']) ?>">
    <meta name="robots" content="<?= e($seoMeta['robots']) ?>">
    <meta name="googlebot" content="<?= e($seoMeta['robots']) ?>">
    <link rel="canonical" href="<?= e($seoMeta['canonical']) ?>">

    <meta property="og:locale" content="<?= e(SITE_LOCALE) ?>">
    <meta property="og:type" content="<?= e($seoMeta['type']) ?>">
    <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
    <meta property="og:title" content="<?= e($seoMeta['title']) ?>">
    <meta property="og:description" content="<?= e($seoMeta['description']) ?>">
    <meta property="og:url" content="<?= e($seoMeta['canonical']) ?>">
    <meta property="og:image" content="<?= e($seoMeta['image']) ?>">
    <meta property="og:image:alt" content="<?= e($seoMeta['image_alt']) ?>">
    <meta property="og:image:width" content="1823">
    <meta property="og:image:height" content="863">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seoMeta['title']) ?>">
    <meta name="twitter:description" content="<?= e($seoMeta['description']) ?>">
    <meta name="twitter:image" content="<?= e($seoMeta['image']) ?>">
    <meta name="twitter:image:alt" content="<?= e($seoMeta['image_alt']) ?>">

    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f5f3ee">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0b0b0d">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="<?= asset_url('assets/icons/favicon.svg') ?>" type="image/svg+xml">
    <link rel="manifest" href="<?= asset_url('site.webmanifest') ?>">
    <link rel="preload" href="<?= url('assets/fonts/vazirmatn.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <?php if ($isHome): ?><link rel="preload" href="<?= asset_url('assets/images/hero/hero-redt.jpg') ?>" as="image" type="image/jpeg" fetchpriority="high"><?php endif; ?>

    <script type="application/ld+json"><?= seo_json_ld($seoMeta) ?></script>
    <script>(function(){try{var t=localStorage.getItem('redt-theme')||(matchMedia('(prefers-color-scheme:dark)').matches?'dark':'light');document.documentElement.dataset.theme=t}catch(e){}})()</script>
    <link rel="stylesheet" href="<?= asset_url('assets/css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/fixes.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/v2.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/formal.css') ?>">
    <?php if ($isHome): ?>
    <link rel="stylesheet" href="<?= asset_url('assets/css/conversion.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/enhancements.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/motion.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/prestige.css') ?>">
    <?php endif; ?>
</head>
<body>
<div class="page-progress" aria-hidden="true"><i></i></div>
<a class="skip-link" href="<?= $isHome ? '#conversion-main' : '#main' ?>">پرش به محتوای اصلی</a>
<header class="header" id="header">
    <div class="container header__inner">
        <a class="logo" href="<?= url() ?>" aria-label="REDT؛ صفحه اصلی"><b>RED</b><span>T</span></a>
        <nav class="nav" aria-label="منوی اصلی">
            <ul>
                <?php foreach ($site['nav'] as $label => $href): ?>
                    <li><a href="<?= url($href) ?>"<?= $isHome && $href === '#home' ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="header__actions">
            <button class="icon-btn theme-toggle" type="button" aria-label="تغییر پوسته">◐</button>
            <a class="btn btn--primary header__cta" href="<?= url('#contact') ?>">مشاوره رایگان</a>
            <button class="icon-btn menu-toggle" type="button" aria-label="بازکردن منو" aria-controls="mobile-menu" aria-expanded="false"><span></span><span></span></button>
        </div>
    </div>
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
        <nav aria-label="منوی موبایل">
            <?php foreach ($site['nav'] as $label => $href): ?>
                <a href="<?= url($href) ?>"><?= e($label) ?><span>↙</span></a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
