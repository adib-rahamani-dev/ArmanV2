<?php
declare(strict_types=1);
require __DIR__ . '/config/config.php';
$token = trim((string) ($_GET['token'] ?? ''));
$order = null;
if (strlen($token) >= 30) {
    try {
        $statement = db()->prepare('SELECT o.tracking_code,o.product_title,o.identity_status,c.full_name FROM orders o JOIN customers c ON c.id=o.customer_id WHERE o.verification_token_hash=?');
        $statement->execute([hash('sha256', $token)]);
        $order = $statement->fetch();
    } catch (Throwable) {}
}
?><!doctype html><html lang="fa-IR" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><meta name="theme-color" content="#0b0b0d"><title>ارسال امن مدارک | REDT</title><link rel="icon" href="<?=asset_url('assets/icons/favicon.svg')?>"><link rel="stylesheet" href="<?=asset_url('assets/css/verification.css')?>"></head><body>
<header><a href="<?=surface_url('digital')?>" class="logo"><b>RED</b>T</a><span><i></i> کانال امن ارسال مدارک</span></header>
<main><?php if(!is_array($order)):?><section class="invalid"><span>!</span><h1>این لینک معتبر نیست</h1><p>احراز هویت فقط از لینک اختصاصی همان سفارش انجام می‌شود.</p><a href="<?=surface_url('digital')?>">بازگشت به فروشگاه</a></section><?php elseif(($order['identity_status']??'')!=='not_submitted'):?><section class="invalid success-state"><span>✓</span><h1>مدارک قبلاً ارسال شده</h1><p>پرونده‌ی <?=e($order['tracking_code'])?> در صف بررسی کارشناسان است.</p><a href="<?=surface_url('digital')?>">بازگشت به فروشگاه</a></section><?php else:?>
<section class="verify-intro"><div><span class="kicker">IDENTITY CHECK</span><h1>تأیید امن<br><em>پرداخت و هویت</em></h1><p>برای جلوگیری از پرداخت با کارت دیگران، مالک سفارش و کارت پرداخت را تطبیق می‌دهیم. مدارک فقط برای بررسی همین سفارش استفاده می‌شوند.</p></div><aside><small>سفارش</small><b><?=e($order['product_title'])?></b><span dir="ltr"><?=e($order['tracking_code'])?></span></aside></section>
<form class="verify-form" action="<?=url('api/verification.php')?>" method="post" enctype="multipart/form-data" data-verification-form>
<input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="token" value="<?=e($token)?>">
<section class="step"><div class="step-no">۱</div><div class="step-copy"><small>مدرک پرداخت</small><h2>فیش واریزی</h2><p>رسید باید مبلغ، زمان و شماره پیگیری خوانا داشته باشد.</p></div><label class="upload"><input type="file" name="receipt" accept="image/jpeg,image/png,image/webp" required data-file><span>＋</span><b>انتخاب تصویر فیش</b><small data-file-name>JPG، PNG یا WebP · حداکثر ۵MB</small></label></section>
<section class="step"><div class="step-no">۲</div><div class="step-copy"><small>تطبیق هویت</small><h2>کارت ملی</h2><p>کد ملی و تصویر روی کارت باید واضح و متعلق به سفارش‌دهنده باشد.</p></div><div class="fields"><label>کد ملی<input name="national_id" dir="ltr" inputmode="numeric" minlength="10" maxlength="10" pattern="[0-9]{10}" required placeholder="0012345678"></label><label class="upload"><input type="file" name="national_card" accept="image/jpeg,image/png,image/webp" required data-file><span>＋</span><b>انتخاب تصویر کارت ملی</b><small data-file-name>تصویر واضح و بدون برش</small></label></div></section>
<section class="step critical"><div class="step-no">۳</div><div class="step-copy"><small>تطبیق کارت پرداخت</small><h2>کارت بانکی</h2><p><strong>حتماً ۸ رقم میانی، CVV2 و تاریخ انقضا را بپوشانید.</strong> فقط نام صاحب کارت و چهار رقم آخر لازم است.</p></div><div class="fields"><label>چهار رقم آخر کارت<input name="card_last4" dir="ltr" inputmode="numeric" minlength="4" maxlength="4" pattern="[0-9]{4}" required placeholder="1234"></label><label class="upload"><input type="file" name="bank_card" accept="image/jpeg,image/png,image/webp" required data-file><span>＋</span><b>انتخاب تصویر کارت</b><small data-file-name>اطلاعات حساس حتماً پوشانده شود</small></label></div></section>
<label class="consent"><input type="checkbox" name="consent" value="1" required><i></i><span>با پردازش امن این مدارک فقط برای تطبیق سفارش موافقم. تصاویر پس از پایان دوره نگهداری حذف می‌شوند.</span></label>
<div class="form-message" role="alert"></div><button class="submit" type="submit"><span>ارسال رمزگذاری‌شده مدارک</span><i>←</i></button><p class="privacy">مدارک در فضای خصوصی نگهداری می‌شوند و از نشانی عمومی قابل مشاهده نیستند.</p>
</form><section class="done" hidden><span>✓</span><h2>مدارک با موفقیت ارسال شد</h2><p>نتیجه بررسی از طریق شماره موبایل سفارش به شما اعلام می‌شود.</p><a href="<?=surface_url('digital')?>">بازگشت به فروشگاه</a></section><?php endif;?></main>
<script src="<?=asset_url('assets/js/verification.js')?>"></script></body></html>
