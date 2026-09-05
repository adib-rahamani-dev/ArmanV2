<?php
declare(strict_types=1);
require __DIR__ . '/config/config.php';
$surface='main';
$pageTitle='REDT | یک شروع روشن برای رشد دیجیتال';
$pageDescription='مسئله کسب‌وکارتان را کوتاه بگویید؛ مسیر مناسب طراحی سایت، تبلیغات، برند یا خدمات دیجیتال را شفاف پیشنهاد می‌دهیم.';
require __DIR__ . '/includes/surface-header.php';
?>
<main>
<section class="landing-hero" id="start">
    <div class="surface-shell">
        <div class="hero-overline"><span><i></i> پذیرش درخواست جدید</span><b>STRATEGY / DESIGN / DIGITAL</b></div>
        <div class="landing-hero__grid">
            <div class="hero-copy">
                <span class="section-kicker">REDT / YOUR DIGITAL PARTNER</span>
                <h1>اول مسیر را<br><em>روشن می‌کنیم.</em></h1>
                <p>سایت، تبلیغات، برند یا ابزار دیجیتال؛ لازم نیست از قبل جواب همه‌چیز را بدانید. مسئله را بگویید تا بهترین نقطهٔ شروع را پیدا کنیم.</p>
                <div class="hero-actions"><a class="surface-btn primary" href="#contact">مسئله‌ام را می‌گویم <i>↙</i></a><a class="surface-btn ghost" href="#paths">دیدن مسیرها</a></div>
                <div class="hero-trust"><span><b>01</b> پاسخ انسانی</span><span><b>02</b> پیشنهاد اختصاصی</span><span><b>03</b> بدون تعهد اولیه</span></div>
            </div>
            <figure class="hero-media">
                <img src="<?=asset_url('assets/images/hero/hero-redt.jpg')?>" alt="فضای خلاقه REDT برای طراحی و رشد دیجیتال" width="1572" height="1001" fetchpriority="high">
                <figcaption><span>فکر روشن</span><span>اجرای دقیق</span></figcaption>
                <div class="hero-media__badge"><b>REDT</b><small>EST. 2026</small></div>
            </figure>
        </div>
        <div class="intent-bar" aria-label="انتخاب سریع نوع درخواست">
            <span>می‌خواهم...</span>
            <a href="#contact" data-service-pick="طراحی سایت"><i>↗</i> یک سایت حرفه‌ای بسازم</a>
            <a href="#contact" data-service-pick="تبلیغات و رشد"><i>↗</i> تبلیغات بهتری اجرا کنم</a>
            <a href="#contact" data-service-pick="خدمات دیجیتال"><i>↗</i> اکانت یا ابزار تهیه کنم</a>
        </div>
    </div>
</section>

<section class="surface-section route-section" id="paths">
    <div class="surface-shell">
        <div class="section-head"><div><span class="section-kicker">TWO FOCUSED SPACES</span><h2>هر نیاز،<br><em>فضای درست خودش.</em></h2></div><p>خدمات تخصصی را از خریدهای دیجیتال جدا کردیم تا بدون منوی شلوغ و مسیرهای گیج‌کننده، مستقیم به چیزی که لازم دارید برسید.</p></div>
        <div class="path-grid">
            <article class="path-card path-studio">
                <div class="path-card__top"><span>01 / REDT STUDIO</span><i>↙</i></div>
                <div><h3>طراحی، برند<br>و رشد کسب‌وکار</h3><p>از ایده و استراتژی تا سایت، کمپین و تجربه‌ای که اعتماد می‌سازد.</p><ul><li>طراحی سایت و محصول</li><li>تبلیغات و کمپین</li><li>هویت و محتوای برند</li></ul></div>
                <a href="<?=surface_url('studio')?>">ورود به استودیو <span>مشاهده پروژه‌ها</span></a>
            </article>
            <article class="path-card path-digital">
                <div class="path-card__top"><span>02 / REDT DIGITAL</span><i>↙</i></div>
                <div><h3>اکانت، اشتراک<br>و ابزار دیجیتال</h3><p>انتخاب سریع، بررسی موجودی و فعال‌سازی امن با پشتیبانی واقعی.</p><ul><li>ابزارهای هوش مصنوعی</li><li>نرم‌افزار و اشتراک</li><li>درخواست سرویس اختصاصی</li></ul></div>
                <a href="<?=surface_url('digital')?>">ورود به دیجیتال <span>مشاهده سرویس‌ها</span></a>
            </article>
        </div>
    </div>
</section>

<section class="surface-section process-section" id="process">
    <div class="surface-shell process-layout">
        <div class="process-intro"><span class="section-kicker">ONE SIMPLE FLOW</span><h2>نه جلسهٔ اضافه؛<br>نه فرم طولانی.</h2><p>برای شروع فقط آن‌قدر اطلاعات می‌گیریم که بتوانیم قدم بعدی را دقیق پیشنهاد بدهیم.</p><a href="#contact">شروع درخواست <i>↓</i></a></div>
        <div class="process-list">
            <article><span>01</span><div><h3>نیازتان را به زبان خودتان بگویید</h3><p>حتی اگر هنوز راه‌حل یا جزئیات فنی را نمی‌دانید.</p></div></article>
            <article><span>02</span><div><h3>درخواست را بررسی می‌کنیم</h3><p>مسیر مناسب، سؤال‌های ضروری و قدم بعدی روشن می‌شود.</p></div></article>
            <article><span>03</span><div><h3>آگاهانه تصمیم می‌گیرید</h3><p>قبل از تأیید شما هیچ پرداخت یا تعهدی وجود ندارد.</p></div></article>
        </div>
    </div>
</section>

<section class="surface-section lead-section" id="contact">
    <div class="surface-shell lead-wrap">
        <div class="lead-copy"><span class="section-kicker">START WITH CLARITY</span><h2>از همین‌جا<br><em>شروع کنیم.</em></h2><p>یک فرم کوتاه، یک پاسخ انسانی و یک مسیر روشن برای ادامه.</p><div class="lead-promise"><span>زمان تکمیل فرم</span><b>کمتر از ۲ دقیقه</b></div></div>
        <form class="lead-form" action="<?=url('api/order.php')?>" method="post" data-lead-form>
            <input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="budget" value="نیازمند گفتگو"><input class="hp" name="website" tabindex="-1" autocomplete="off">
            <fieldset><legend>برای چه چیزی کمک می‌خواهید؟</legend><div class="choice-pills"><label><input type="radio" name="service" value="طراحی سایت" required><span>طراحی سایت</span></label><label><input type="radio" name="service" value="تبلیغات و رشد"><span>تبلیغات و رشد</span></label><label><input type="radio" name="service" value="برندینگ"><span>برندینگ</span></label><label><input type="radio" name="service" value="خدمات دیجیتال"><span>خدمات دیجیتال</span></label></div></fieldset>
            <label>هدفتان را کوتاه بنویسید<textarea name="message" rows="4" minlength="10" required placeholder="مثلاً می‌خواهم برای کسب‌وکارم یک سایت حرفه‌ای داشته باشم..."></textarea></label>
            <div class="lead-form__row"><label>نام و نام خانوادگی<input name="name" required minlength="3" autocomplete="name" placeholder="نام شما"></label><label>شماره موبایل<input name="phone" required inputmode="tel" dir="ltr" autocomplete="tel" placeholder="0912 000 0000"></label></div>
            <small class="form-message" role="alert"></small><button type="submit">ثبت درخواست <i>↙</i></button><small class="lead-privacy">اطلاعات شما فقط برای پاسخ به همین درخواست استفاده می‌شود.</small>
        </form>
    </div>
</section>
</main>
<?php require __DIR__ . '/includes/surface-footer.php'; ?>
