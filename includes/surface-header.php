<?php
declare(strict_types=1);
$surface = $surface ?? 'main';
$pageTitle = $pageTitle ?? 'REDT';
$pageDescription = $pageDescription ?? 'طراحی، رشد و ابزارهای دیجیتال برای کسب‌وکارهای آینده‌نگر.';
$surfaceLabel = ['main'=>'START','studio'=>'STUDIO','digital'=>'DIGITAL'][$surface] ?? 'START';
$surfaceNav = match($surface) {
    'digital' => ['فروشگاه'=>'#catalog','چطور کار می‌کند'=>'#how','سفارش اختصاصی'=>'#support'],
    'studio' => ['تخصص‌ها'=>'#services','پروژه‌ها'=>'#work','شروع همکاری'=>'#contact'],
    default => ['مسیر مناسب'=>'#paths','روش همکاری'=>'#process','شروع درخواست'=>'#contact'],
};
$ctaTarget = $surface === 'digital' ? '#catalog' : '#contact';
$ctaLabel = $surface === 'digital' ? 'انتخاب سرویس' : 'شروع کنیم';
$surfaceBase = surface_url($surface);
$surfaceCanonical = preg_match('~^https?://~i', $surfaceBase) === 1
    ? $surfaceBase
    : absolute_url($surface === 'main' ? '' : $surface . '/');
track_page_view();
?><!doctype html>
<html lang="fa-IR" dir="rtl" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title><?=e($pageTitle)?></title>
    <meta name="description" content="<?=e($pageDescription)?>">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="<?=e($surfaceCanonical)?>">
    <meta name="theme-color" content="#08080a">
    <link rel="icon" href="<?=asset_url('assets/icons/favicon.svg')?>" type="image/svg+xml">
    <link rel="preload" href="<?=url('assets/fonts/Vazirmatn.woff2')?>" as="font" type="font/woff2" crossorigin>
    <script>(function(){try{document.documentElement.dataset.theme=localStorage.getItem('redt-surface-theme')||'dark'}catch(e){}})()</script>
    <link rel="stylesheet" href="<?=asset_url('assets/css/surfaces.css')?>">
</head>
<body class="surface-body surface-<?=e($surface)?>">
<a class="surface-skip" href="#start">رفتن به محتوای اصلی</a>
<header class="surface-header">
    <div class="surface-shell surface-header__inner">
        <a class="surface-logo" href="<?=surface_url('main')?>" aria-label="REDT؛ صفحه اصلی">
            <span><b>RED</b>T</span><small><?=e($surfaceLabel)?></small>
        </a>
        <nav class="surface-nav" aria-label="منوی این بخش">
            <?php foreach($surfaceNav as $label=>$href):?><a href="<?=e($href)?>"><?=e($label)?></a><?php endforeach;?>
        </nav>
        <div class="surface-network" aria-label="انتخاب بخش">
            <span>REDT NETWORK</span>
            <a class="<?=$surface==='main'?'active':''?>" href="<?=surface_url('main')?>">خانه</a>
            <a class="<?=$surface==='studio'?'active':''?>" href="<?=surface_url('studio')?>">استودیو</a>
            <a class="<?=$surface==='digital'?'active':''?>" href="<?=surface_url('digital')?>">دیجیتال</a>
        </div>
        <a class="surface-header__cta" href="<?=e($ctaTarget)?>"><?=e($ctaLabel)?><i>↙</i></a>
        <button class="surface-theme" type="button" aria-label="تغییر پوسته"><span>◐</span></button>
        <button class="surface-menu" type="button" aria-label="بازکردن منو" aria-expanded="false"><i></i><i></i></button>
    </div>
    <div class="surface-mobile-menu" aria-hidden="true">
        <div class="surface-mobile-menu__links"><?php foreach($surfaceNav as $label=>$href):?><a href="<?=e($href)?>"><?=e($label)?><span>↙</span></a><?php endforeach;?></div>
        <div class="surface-mobile-menu__network"><span>انتخاب فضای REDT</span><a href="<?=surface_url('main')?>">خانه</a><a href="<?=surface_url('studio')?>">استودیو</a><a href="<?=surface_url('digital')?>">دیجیتال</a></div>
    </div>
</header>
