<?php
declare(strict_types=1);

$surface = $surface ?? 'main';
$pageTitle = $pageTitle ?? 'REDT';
$pageDescription = $pageDescription ?? 'ایده، اجرا و رشد در یک مسیر روشن.';
$surfaceLabel = ['main'=>'REDT','studio'=>'STUDIO','digital'=>'DIGITAL','arman'=>'ARMAN'][$surface] ?? 'REDT';
$surfaceNav = match ($surface) {
    'digital' => ['محصولات'=>'#catalog','نحوه خرید'=>'#how','پیگیری سفارش'=>'#tracking','پشتیبانی'=>'#support'],
    'studio' => ['خدمات'=>'#services','پروژه‌ها'=>'#work','رویکرد'=>'#process'],
    'arman' => ['خانه'=>'#start','رویدادها'=>'#events','نوشته‌ها'=>'#articles','درباره آرمان'=>'#about'],
    default => ['راهکارها'=>'#paths','چرا REDT'=>'#why','روند همکاری'=>'#process','سؤالات متداول'=>'#faq'],
};
$ctaTarget = match ($surface) {
    'digital' => '#catalog',
    'studio' => '#contact',
    'arman' => '#events',
    default => '#consultation',
};
$ctaLabel = match ($surface) {
    'digital' => 'مشاهده محصولات',
    'studio' => 'شروع پروژه',
    'arman' => 'مشاهده رویدادها',
    default => 'درخواست مشاوره',
};
$surfaceBase = surface_url($surface);
$surfaceCanonical = preg_match('~^https?://~i', $surfaceBase) === 1
    ? $surfaceBase
    : absolute_url($surface === 'main' ? '' : $surface . '/');
$seoMeta = seo_meta([
    'title' => $pageTitle,
    'description' => $pageDescription,
    'canonical' => $surfaceCanonical,
    'robots' => $pageRobots ?? null,
    'schema' => $pageSchema ?? [],
]);
track_page_view();
?><!doctype html>
<html lang="fa-IR" dir="rtl" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title><?=e($seoMeta['title'])?></title>
    <meta name="description" content="<?=e($seoMeta['description'])?>">
    <meta name="robots" content="<?=e($seoMeta['robots'])?>">
    <link rel="canonical" href="<?=e($seoMeta['canonical'])?>">
    <meta name="theme-color" content="#0B0C0F">
    <meta name="color-scheme" content="light dark">
    <link rel="icon" href="<?=asset_url('assets/icons/favicon.svg')?>" type="image/svg+xml">
    <link rel="manifest" href="<?=url('site.webmanifest')?>">
    <link rel="preload" href="<?=url('assets/fonts/Vazirmatn.woff2')?>" as="font" type="font/woff2" crossorigin>
    <script>(function(){var d=document.documentElement;d.className=d.className.replace('no-js','js');try{var t=localStorage.getItem('redt-theme');d.dataset.theme=t==='light'||t==='dark'?t:(matchMedia('(prefers-color-scheme:light)').matches?'light':'dark')}catch(e){d.dataset.theme='dark'}})()</script>
    <link rel="stylesheet" href="<?=asset_url('assets/css/surfaces.css')?>">
    <?php if(in_array($surface,['studio','digital'],true)):?><link rel="stylesheet" href="<?=asset_url('assets/css/conversion-v2.css')?>"><?php endif;?>
    <link rel="stylesheet" href="<?=asset_url('assets/css/neo-system.css')?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fa_IR">
    <meta property="og:title" content="<?=e($seoMeta['title'])?>">
    <meta property="og:description" content="<?=e($seoMeta['description'])?>">
    <meta property="og:url" content="<?=e($seoMeta['canonical'])?>">
    <meta property="og:image" content="<?=e($seoMeta['image'])?>">
    <meta name="twitter:card" content="summary_large_image">
    <script type="application/ld+json"><?=seo_json_ld($seoMeta)?></script>
</head>
<body class="surface-body surface-<?=e($surface)?>">
<a class="surface-skip" href="#start">رفتن به محتوای اصلی</a>
<header class="surface-header" data-site-header>
    <div class="surface-shell surface-header__inner">
        <a class="surface-logo" href="<?=surface_url('main')?>" aria-label="REDT؛ صفحه اصلی">
            <span><b>RED</b>T</span><small><?=e($surfaceLabel)?></small>
        </a>
        <nav class="neo-primary-nav" aria-label="ناوبری اصلی">
            <?php foreach($surfaceNav as $label=>$href):?><a href="<?=e($href)?>"><?=e($label)?></a><?php endforeach;?>
        </nav>
        <details class="neo-network">
            <summary>REDT Network</summary>
            <div>
                <a href="<?=surface_url('main')?>"><b>REDT</b><span>مشاوره و انتخاب مسیر</span></a>
                <a href="<?=surface_url('studio')?>"><b>Studio</b><span>طراحی و تولید خلاق</span></a>
                <a href="<?=surface_url('digital')?>"><b>Digital</b><span>محصولات و سرویس‌ها</span></a>
                <a href="<?=surface_url('arman')?>"><b>Arman Rajaei</b><span>رویداد و جامعه</span></a>
            </div>
        </details>
        <button class="surface-theme" type="button" aria-label="تغییر به حالت روشن" aria-pressed="false" title="تغییر پوسته"><span aria-hidden="true">◐</span></button>
        <a class="surface-header__cta" href="<?=e($ctaTarget)?>"><?=e($ctaLabel)?><i aria-hidden="true">←</i></a>
        <button class="surface-menu" type="button" aria-label="بازکردن منو" aria-expanded="false" aria-controls="surface-mobile-menu"><i></i><i></i></button>
    </div>
    <div class="surface-progress" aria-hidden="true"><i></i></div>
    <div class="surface-mobile-menu" id="surface-mobile-menu" aria-hidden="true">
        <nav class="surface-mobile-menu__links" aria-label="ناوبری موبایل"><?php foreach($surfaceNav as $label=>$href):?><a href="<?=e($href)?>"><?=e($label)?><span aria-hidden="true">←</span></a><?php endforeach;?></nav>
        <a class="neo-mobile-cta" href="<?=e($ctaTarget)?>"><?=e($ctaLabel)?></a>
        <div class="surface-mobile-menu__network"><span>فضاهای REDT</span><a href="<?=surface_url('main')?>">REDT</a><a href="<?=surface_url('studio')?>">Studio</a><a href="<?=surface_url('digital')?>">Digital</a><a href="<?=surface_url('arman')?>">Arman</a></div>
    </div>
</header>
