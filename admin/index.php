<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';

$loginError = '';
if (is_post() && ($_POST['action'] ?? '') === 'login') {
    $attempts = (int) ($_SESSION['admin_login_attempts'] ?? 0);
    if ($attempts >= 8) {
        $loginError = 'تعداد تلاش‌ها زیاد است؛ مرورگر را ببندید و چند دقیقه بعد دوباره امتحان کنید.';
    } elseif (!csrf_valid($_POST['csrf'] ?? null)) {
        $loginError = 'نشست منقضی شده است؛ صفحه را تازه کنید.';
    } elseif (admin_credentials_valid(trim((string) ($_POST['username'] ?? '')), (string) ($_POST['password'] ?? ''))) {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = time();
        $_SESSION['admin_login_attempts'] = 0;
        header('Location: ' . url('admin/'));
        exit;
    } else {
        $_SESSION['admin_login_attempts'] = $attempts + 1;
        $loginError = 'نام کاربری یا رمز عبور درست نیست.';
    }
}
if (is_post() && ($_POST['action'] ?? '') === 'logout' && admin_is_authenticated() && csrf_valid($_POST['csrf'] ?? null)) {
    unset($_SESSION['admin_authenticated']);
    session_regenerate_id(true);
    header('Location: ' . url('admin/'));
    exit;
}

if (!admin_is_authenticated()):
?><!doctype html><html lang="fa-IR" dir="rtl" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>ورود به مرکز مدیریت | REDT</title><link rel="icon" href="<?=asset_url('assets/icons/favicon.svg')?>"><link rel="stylesheet" href="<?=asset_url('assets/css/panel.css')?>"><link rel="stylesheet" href="<?=asset_url('assets/css/admin-live.css')?>"></head><body class="admin-login"><main><a class="panel-logo" href="<?=url()?>"><b>RED</b>T</a><section><span class="login-kicker">SECURE CONTROL CENTER</span><h1>مرکز مدیریت<br><em>REDT</em></h1><p>برای مدیریت سفارش‌ها، فروشگاه و گزارش‌های زنده وارد شوید.</p><?php if($loginError):?><div class="login-error" role="alert"><?=e($loginError)?></div><?php endif;?><form method="post"><input type="hidden" name="action" value="login"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>نام کاربری<input name="username" autocomplete="username" required autofocus placeholder="admin"></label><label>رمز عبور<input name="password" type="password" autocomplete="current-password" required placeholder="••••••••"></label><button type="submit">ورود امن به پنل <i>←</i></button></form><?php if(APP_ENV!=='production' && !getenv('ADMIN_PASSWORD')):?><small class="dev-hint">ورود محیط توسعه: <b>admin</b> / <b>redt-admin</b></small><?php endif;?></section><aside><span>01 / ORDERS</span><span>02 / CATALOG</span><span>03 / ANALYTICS</span></aside></main></body></html><?php exit; endif;

$analytics = analytics_summary(14);
$orders = admin_order_rows();
$catalog = require __DIR__ . '/../data/catalog.php';
$serviceOrders = count(array_filter($orders, static fn(array $row): bool => $row['_source'] === 'service'));
$purchaseOrders = count($orders) - $serviceOrders;
$newOrders = count(array_filter($orders, static fn(array $row): bool => $row['_status'] === 'new'));
$conversion = $analytics['unique'] > 0 ? round(count($orders) / $analytics['unique'] * 100, 1) : 0;
$published = 0; $catalogCount = 0;
foreach (['products','accounts','courses','ads'] as $group) foreach (($catalog[$group] ?? []) as $entry) { $catalogCount++; if ($entry['active'] ?? true) $published++; }
$statusLabels = ['new'=>'جدید','reviewing'=>'در حال بررسی','contacted'=>'تماس گرفته شد','paid'=>'پرداخت شده','doing'=>'در حال انجام','done'=>'تکمیل شده','cancelled'=>'لغو شده'];
$groupLabels = ['products'=>'محصول','accounts'=>'اکانت','courses'=>'دوره','ads'=>'تبلیغات'];
$nav = ['overview'=>['نمای کلی','⌂'],'orders'=>['سفارش‌ها','□'],'commerce'=>['فروشگاه','▣'],'reports'=>['گزارش‌ها','⌁'],'content'=>['محتوا','✎'],'customers'=>['مشتریان','♙'],'messages'=>['پیام‌ها','◌'],'settings'=>['تنظیمات','⚙']];
$metrics = [
 ['label'=>'کل درخواست‌ها','value'=>fa_number(count($orders)),'change'=>fa_number($newOrders).' مورد جدید','tone'=>'red','icon'=>'□'],
 ['label'=>'سفارش خرید','value'=>fa_number($purchaseOrders),'change'=>'اکانت و خدمات دیجیتال','tone'=>'green','icon'=>'↗'],
 ['label'=>'بازدید ۱۴ روز','value'=>fa_number($analytics['total']),'change'=>fa_number($analytics['unique']).' نشست یکتا','tone'=>'blue','icon'=>'◌'],
 ['label'=>'نرخ تبدیل درخواست','value'=>strtr((string)$conversion,['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹','.'=>'٫']).'٪','change'=>'درخواست نسبت به نشست','tone'=>'amber','icon'=>'⌁'],
];
?><!doctype html>
<html lang="fa-IR" dir="rtl" data-theme="light">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><meta name="theme-color" content="#ed111c"><title>مرکز مدیریت REDT</title><link rel="icon" href="<?=asset_url('assets/icons/favicon.svg')?>"><link rel="stylesheet" href="<?=asset_url('assets/css/panel.css')?>"><link rel="stylesheet" href="<?=asset_url('assets/css/panel-inventory.css')?>"><link rel="stylesheet" href="<?=asset_url('assets/css/admin-live.css')?>"><script>window.REDT_ADMIN={endpoint:<?=json_encode(url('admin/api.php'))?>,csrf:<?=json_encode(csrf_token())?>};</script></head>
<body class="panel-body">
<aside class="sidebar" id="sidebar"><div class="sidebar__brand"><a href="<?=url()?>" class="panel-logo"><b>RED</b>T</a><button class="sidebar-close" data-sidebar aria-label="بستن">×</button></div><div class="admin-card"><span class="avatar">آر</span><div><b>مدیر REDT</b><small>دسترسی کامل</small></div><i></i></div><nav class="side-nav" aria-label="منوی مدیریت"><span class="side-label">مرکز عملیات</span><?php foreach($nav as $key=>$item):?><a href="#<?=e($key)?>" data-view-link="<?=e($key)?>"><i><?=e($item[1])?></i><span><?=e($item[0])?></span><?=$key==='orders'&&$newOrders?'<em>'.fa_number($newOrders).'</em>':''?></a><?php endforeach;?></nav><div class="sidebar__footer"><a href="<?=url()?>">↗ مشاهده سایت</a><button class="theme-switch" type="button">◐ تغییر پوسته</button><form method="post"><input type="hidden" name="action" value="logout"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><button class="admin-logout" type="submit">خروج امن</button></form></div></aside>
<main class="panel-main"><header class="topbar"><button class="menu-btn" data-sidebar aria-label="منو">☰</button><div class="search"><span>⌕</span><input type="search" data-admin-search placeholder="جستجو در سفارش‌ها و مشتریان..." aria-label="جستجو"><kbd>Ctrl K</kbd></div><div class="top-actions"><span class="live-badge"><i></i> داده زنده</span><a class="primary-action" href="<?=surface_url('digital')?>">↗ بخش دیجیتال</a></div></header><div class="panel-content">
<section class="panel-view active" data-view="overview"><div class="page-head"><div><span class="date">داشبورد لحظه‌ای · <?=e(date('Y/m/d'))?></span><h1>مرکز عملیات <em>REDT</em></h1><p>فروش، درخواست‌ها و رفتار کاربران بر اساس داده واقعی سایت.</p></div><a class="outline-action" href="#reports">مشاهده گزارش کامل ←</a></div><div class="metric-grid"><?php foreach($metrics as $m):?><article class="metric-card"><div class="metric-icon <?=e($m['tone'])?>"><?=e($m['icon'])?></div><span><?=e($m['label'])?></span><strong><?=e($m['value'])?></strong><small><?=e($m['change'])?></small></article><?php endforeach;?></div><div class="dashboard-grid"><article class="panel-card chart-card"><div class="card-head"><div><h2>روند بازدید واقعی</h2><p>۱۴ روز گذشته</p></div><span class="chart-total"><?=fa_number($analytics['total'])?> بازدید</span></div><div class="live-chart"><?php $max=max(1,...array_column($analytics['series'],'views'));foreach($analytics['series'] as $point):?><i style="--h:<?=max(5,round($point['views']/$max*100))?>%" title="<?=e($point['date'])?>: <?=fa_number($point['views'])?>"><b><?=e($point['label'])?></b></i><?php endforeach;?></div></article><article class="panel-card activity-card"><div class="card-head"><div><h2>خلاصه ورودی‌ها</h2><p>بر اساس درخواست‌های ثبت‌شده</p></div></div><div class="operations-summary"><div><strong><?=fa_number($serviceOrders)?></strong><span>سفارش خدمات</span></div><div><strong><?=fa_number($purchaseOrders)?></strong><span>سفارش خرید</span></div><div><strong><?=fa_number($published)?></strong><span>آیتم منتشرشده</span></div><div><strong><?=fa_number($newOrders)?></strong><span>نیازمند پیگیری</span></div></div></article></div><?=render_admin_orders($orders,$statusLabels,6)?></section>

<section class="panel-view" data-view="orders"><div class="page-head compact"><div><span class="eyebrow">فروش و عملیات</span><h1>سفارش‌ها</h1><p>همه درخواست‌های خدمات، خرید اکانت، محصول و تبلیغات.</p></div><div class="order-filters"><button class="active" data-order-filter="all">همه</button><button data-order-filter="new">جدید</button><button data-order-filter="purchase">خرید</button><button data-order-filter="service">خدمات</button></div></div><?=render_admin_orders($orders,$statusLabels,0)?></section>

<section class="panel-view" data-view="commerce"><div class="page-head compact"><div><span class="eyebrow">کاتالوگ مرکزی</span><h1>فروشگاه و انتشار</h1><p><?=fa_number($published)?> آیتم از <?=fa_number($catalogCount)?> آیتم هم‌اکنون در سایت فعال است.</p></div><a class="primary-action" href="<?=surface_url('digital')?>">مشاهده فروشگاه ↗</a></div><article class="panel-card inventory-card"><div class="card-head"><div><h2>کنترل انتشار فوری</h2><p>تغییرات بدون ویرایش کد روی فروشگاه اعمال می‌شوند.</p></div></div><div class="inventory-list"><?php foreach($groupLabels as $group=>$label):foreach(($catalog[$group]??[]) as $entry):$enabled=$entry['active']??true;?><div data-inventory-item><span class="inventory-thumb"><?=e(strtoupper(substr($entry['id'],0,2)))?></span><p><b><?=e($entry['title'])?></b><small><?=e($label)?> · وضعیت انتشار</small></p><label class="publish-switch"><input type="checkbox" <?=$enabled?'checked':''?> data-publish data-group="<?=e($group)?>" data-id="<?=e($entry['id'])?>"><i></i><em><?=$enabled?'فعال':'غیرفعال'?></em></label></div><?php endforeach;endforeach;?></div></article></section>

<section class="panel-view" data-view="reports"><div class="page-head compact"><div><span class="eyebrow">تحلیل رفتار</span><h1>گزارش ۱۴ روزه</h1><p>مسیرهای پربازدید و نوع دستگاه کاربران.</p></div></div><div class="dashboard-grid"><article class="panel-card"><div class="card-head"><div><h2>صفحه‌های پربازدید</h2><p>بر اساس بازدید واقعی</p></div></div><div class="path-report"><?php if(!$analytics['paths']):?><p class="admin-empty">هنوز بازدیدی ثبت نشده است.</p><?php else:$pathMax=max(1,...array_values($analytics['paths']));foreach($analytics['paths'] as $path=>$count):?><div><span dir="ltr"><?=e($path)?></span><i><b style="width:<?=round($count/$pathMax*100)?>%"></b></i><strong><?=fa_number($count)?></strong></div><?php endforeach;endif;?></div></article><article class="panel-card"><div class="card-head"><div><h2>دستگاه کاربران</h2><p>دسکتاپ در برابر موبایل</p></div></div><?php $deviceTotal=max(1,array_sum($analytics['devices']));?><div class="device-chart" style="--mobile:<?=round($analytics['devices']['mobile']/$deviceTotal*100)?>%"><div><strong><?=fa_number($analytics['devices']['mobile'])?></strong><span>موبایل</span></div><div><strong><?=fa_number($analytics['devices']['desktop'])?></strong><span>دسکتاپ</span></div></div></article></div></section>

<?php $placeholders=['content'=>['مدیریت محتوا','صفحات، مقاله‌ها و نمونه‌کارها'],'customers'=>['مشتریان','پروفایل و تاریخچه ارتباط'],'messages'=>['پیام‌ها','درخواست‌های مشاوره و پشتیبانی'],'settings'=>['تنظیمات سایت','هویت برند، سئو و امنیت']];foreach($placeholders as $key=>$copy):?><section class="panel-view" data-view="<?=e($key)?>"><div class="page-head compact"><div><span class="eyebrow">ماژول توسعه‌پذیر</span><h1><?=e($copy[0])?></h1><p><?=e($copy[1])?></p></div></div><article class="panel-card empty-workspace"><div class="empty-icon">✦</div><h2><?=e($copy[0])?></h2><p>زیرساخت این ماژول آماده است و در فاز اتصال دیتابیس اصلی، عملیات کامل آن فعال می‌شود.</p></article></section><?php endforeach;?>
</div></main><div class="toast" role="status" aria-live="polite"></div><script src="<?=asset_url('assets/js/panel.js')?>"></script></body></html>
<?php
function render_admin_orders(array $orders, array $statusLabels, int $limit = 0): string {
    $visible = $limit > 0 ? array_slice($orders, 0, $limit) : $orders;
    ob_start(); ?>
    <article class="panel-card table-card admin-orders"><div class="card-head"><div><h2><?=$limit?'آخرین سفارش‌ها':'مرکز پیگیری سفارش‌ها'?></h2><p><?=fa_number(count($orders))?> درخواست ثبت‌شده</p></div><?=$limit?'<a href="#orders">مشاهده همه ←</a>':''?></div><div class="table-scroll"><table><thead><tr><th>شناسه</th><th>مشتری</th><th>نوع / عنوان</th><th>موبایل</th><th>تاریخ</th><th>وضعیت</th></tr></thead><tbody><?php if(!$visible):?><tr><td colspan="6" class="admin-empty">هنوز سفارشی ثبت نشده است.</td></tr><?php else:foreach($visible as $row):$date=!empty($row['date'])?date('Y/m/d H:i',strtotime((string)$row['date'])):'—';?><tr data-order-row data-source="<?=e($row['_source'])?>" data-state="<?=e($row['_status'])?>" data-search="<?=e(($row['name']??'').' '.($row['_title']??'').' '.($row['phone']??'').' '.($row['order_id']??''))?>"><td dir="ltr"><?=e($row['order_id']??('RED-'.strtoupper(substr($row['_key'],0,7))))?></td><td><b><?=e($row['name']??'بدون نام')?></b></td><td><span class="order-kind <?=e($row['_source'])?>"><?=$row['_source']==='purchase'?'خرید':'خدمات'?></span><?=e($row['_title'])?></td><td dir="ltr"><?=e($row['phone']??'—')?></td><td dir="ltr"><?=e($date)?></td><td><select class="status-select status-<?=e($row['_status'])?>" data-order-status data-key="<?=e($row['_key'])?>"><?php foreach($statusLabels as $value=>$label):?><option value="<?=e($value)?>" <?=$value===$row['_status']?'selected':''?>><?=e($label)?></option><?php endforeach;?></select></td></tr><?php endforeach;endif;?></tbody></table></div></article>
    <?php return (string) ob_get_clean();
}
