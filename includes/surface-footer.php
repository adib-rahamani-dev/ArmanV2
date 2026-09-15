<?php
declare(strict_types=1);
$footerHeading = match ($surface) {
    'studio' => 'اگر مسئله‌ای برای ساختن دارید، از یک گفت‌وگوی روشن شروع کنیم.',
    'digital' => 'سرویس مناسب را پیدا نکردید؟ درخواستتان را برای بررسی بفرستید.',
    'arman' => 'برای رویدادها و اتفاق‌های تازه، همین‌جا دوباره سر بزنید.',
    default => 'برای رشد کسب‌وکارتان، از یک گفت‌وگوی ساده شروع کنید.',
};
$contactEmail = trim((string) (getenv('CONTACT_EMAIL') ?: ''));
$contactPhone = trim((string) (getenv('CONTACT_PHONE') ?: ''));
?>
<footer class="surface-footer neo-footer">
    <div class="surface-shell neo-footer__grid">
        <div class="neo-footer__statement">
            <a class="surface-logo" href="<?=surface_url('main')?>" aria-label="REDT؛ صفحه اصلی"><span><b>RED</b>T</span><small>NETWORK</small></a>
            <h2><?=e($footerHeading)?></h2>
            <?php if($surface === 'main'):?><a class="ncc-button ncc-button--primary" href="#consultation">درخواست مشاوره <span aria-hidden="true">←</span></a><?php endif;?>
        </div>
        <nav class="neo-footer__links" aria-label="فضاهای REDT">
            <span>اکوسیستم REDT</span>
            <a href="<?=surface_url('main')?>"><b>REDT</b><small>مشاوره و انتخاب مسیر</small></a>
            <a href="<?=surface_url('studio')?>"><b>REDT Studio</b><small>طراحی، کمپین و تولید</small></a>
            <a href="<?=surface_url('digital')?>"><b>REDT Digital</b><small>محصول و سرویس دیجیتال</small></a>
            <a href="<?=surface_url('arman')?>"><b>Arman Rajaei</b><small>رویداد، نوشته و جامعه</small></a>
        </nav>
        <nav class="neo-footer__links" aria-label="اطلاعات حقوقی و تماس">
            <span>ارتباط و شفافیت</span>
            <?php if($contactEmail !== ''):?><a href="mailto:<?=e($contactEmail)?>" dir="ltr"><?=e($contactEmail)?></a><?php endif;?>
            <?php if($contactPhone !== ''):?><a href="tel:<?=e(preg_replace('/\s+/','',$contactPhone) ?? '')?>" dir="ltr"><?=e($contactPhone)?></a><?php endif;?>
            <a href="<?=url('privacy')?>">حریم خصوصی</a>
            <a href="<?=url('terms')?>">شرایط استفاده</a>
            <?php if($contactEmail === '' && $contactPhone === ''):?><small>اطلاعات تماس مستقیم پس از تأیید مدیریت نمایش داده می‌شود.</small><?php endif;?>
        </nav>
    </div>
    <div class="surface-shell neo-footer__bottom"><span>© <?=date('Y')?> REDT</span><span>NEO-CORPORATE CREATIVE INTELLIGENCE</span><button class="surface-theme neo-footer-theme" type="button" aria-label="تغییر پوسته"><span aria-hidden="true">◐</span> روشن / تیره</button></div>
</footer>
<div class="surface-toast" role="status" aria-live="polite"></div>
<script src="<?=asset_url('assets/js/surfaces.js')?>" defer></script>
<script src="<?=asset_url('assets/js/neo-system.js')?>" defer></script>
</body>
</html>
