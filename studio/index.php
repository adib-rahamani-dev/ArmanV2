<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';
$surface='studio';
$pageTitle='REDT Studio | طراحی سایت، برند و تبلیغات';
$pageDescription='استودیوی طراحی و رشد REDT؛ طراحی سایت، تجربه محصول، هویت برند و کمپین‌های تبلیغاتی در یک مسیر یکپارچه.';
require __DIR__ . '/../includes/surface-header.php';
?>
<main>
<section class="studio-hero" id="start">
    <div class="surface-shell studio-hero__grid">
        <div class="hero-copy"><span class="section-kicker">REDT / INDEPENDENT CREATIVE STUDIO</span><h1>برندهایی می‌سازیم<br>که <em>واضح‌تر</em><br>انتخاب می‌شوند.</h1><p>استراتژی، طراحی و رشد را به یک تجربهٔ منسجم تبدیل می‌کنیم؛ از اولین برخورد کاربر تا لحظه‌ای که تصمیم می‌گیرد.</p><div class="hero-actions"><a class="surface-btn primary" href="#contact">پروژه‌ام را شروع می‌کنم <i>↙</i></a><a class="surface-btn ghost" href="#work">دیدن پروژه‌ها</a></div></div>
        <figure class="studio-hero__media"><img src="<?=asset_url('assets/images/case-studies/architecture-v2.webp')?>" alt="طراحی تجربه وب توسط استودیوی REDT" width="1400" height="900" fetchpriority="high"><figcaption><b>آوان / معماری</b><span>STRATEGY · WEB DESIGN · 2026</span></figcaption></figure>
        <aside class="studio-manifesto"><span>OUR APPROACH</span><p>زیبایی وقتی ارزشمند است که فهمیدن، اعتمادکردن و اقدام‌کردن را ساده‌تر کند.</p><div><i></i> پذیرش محدود پروژه</div></aside>
    </div>
    <div class="surface-shell studio-capabilities"><span>WEB & PRODUCT</span><span>BRAND SYSTEM</span><span>CAMPAIGN & GROWTH</span><span>CONTENT DIRECTION</span></div>
</section>

<section class="surface-section expertise-section" id="services"><div class="surface-shell"><div class="section-head"><div><span class="section-kicker">WHAT WE SOLVE</span><h2>سه تخصص؛<br><em>یک تصویر منسجم.</em></h2></div><p>هر همکاری حول یک مسئلهٔ واقعی شکل می‌گیرد، نه فهرستی از خروجی‌های پراکنده.</p></div><div class="expertise-list">
    <article><span>01</span><div><small>DIGITAL EXPERIENCE</small><h3>طراحی سایت و محصول</h3><p>ساختار، محتوا و رابط را کنار هم می‌چینیم تا تجربه سریع، قابل فهم و آمادهٔ رشد باشد.</p></div><ul><li>لندینگ و سایت شرکتی</li><li>فروشگاه و پنل کاربری</li><li>UX / UI و Design System</li></ul><i>↙</i></article>
    <article><span>02</span><div><small>GROWTH SYSTEM</small><h3>تبلیغات و کمپین</h3><p>از پیام و لندینگ تا انتخاب رسانه و گزارش؛ یک کمپین یکپارچه با هدف روشن.</p></div><ul><li>کمپین شبکه‌های اجتماعی</li><li>اینفلوئنسر مارکتینگ</li><li>استراتژی محتوا و رشد</li></ul><i>↙</i></article>
    <article><span>03</span><div><small>BRAND CLARITY</small><h3>هویت و روایت برند</h3><p>سیستمی منعطف برای اینکه برند در هر نقطهٔ تماس یک صدا و یک تصویر داشته باشد.</p></div><ul><li>جایگاه و استراتژی برند</li><li>هویت بصری و راهنما</li><li>لحن و سیستم محتوایی</li></ul><i>↙</i></article>
</div></div></section>

<section class="surface-section work-section" id="work"><div class="surface-shell"><div class="section-head"><div><span class="section-kicker">SELECTED PROJECTS</span><h2>تصمیم‌های دقیق،<br><em>نتیجه‌های دیدنی.</em></h2></div><p>هر پروژه با یک سؤال شروع شده و به سیستمی رسیده که کاربر واقعاً می‌تواند با آن ارتباط بگیرد.</p></div><div class="work-grid">
    <article class="case-card case-wide"><a href="<?=url('page.php?type=project&id=nova')?>"><img src="<?=asset_url('assets/images/case-studies/fintech-v2.webp')?>" alt="طراحی محصول نئوبانک نوا"><div><span>محصول دیجیتال / فین‌تک</span><h3>یک تجربهٔ بانکی<br>آرام‌تر برای نُوا</h3><small>UX · UI · DESIGN SYSTEM</small></div><i>↙</i></a></article>
    <article class="case-card"><a href="<?=url('page.php?type=project&id=avan')?>"><img src="<?=asset_url('assets/images/case-studies/architecture-v2.webp')?>" alt="طراحی وب‌سایت استودیو آوان"><div><span>وب‌سایت / معماری</span><h3>آوان؛ فضا در قاب دیجیتال</h3><small>STRATEGY · WEB DESIGN</small></div><i>↙</i></a></article>
    <article class="case-card"><a href="<?=url('page.php?type=project&id=rost')?>"><img src="<?=asset_url('assets/images/case-studies/coffee-v2.webp')?>" alt="هویت بصری کافه رست"><div><span>هویت / بسته‌بندی</span><h3>رُست؛ هویتی گرم و ملموس</h3><small>BRAND · PACKAGING</small></div><i>↙</i></a></article>
</div></div></section>

<section class="surface-section process-section" id="process"><div class="surface-shell process-layout"><div class="process-intro"><span class="section-kicker">HOW WE WORK</span><h2>شفاف از ابتدا<br>تا تحویل.</h2><p>در هر مرحله می‌دانید چه چیزی، چرا و برای چه زمانی ساخته می‌شود.</p><a href="#contact">گفت‌وگوی اولیه <i>↓</i></a></div><div class="process-list"><article><span>01</span><div><h3>کشف و هم‌راستایی</h3><p>مسئله، مخاطب، هدف و محدودیت‌ها را به یک تعریف مشترک می‌رسانیم.</p></div></article><article><span>02</span><div><h3>مسیر و نمونهٔ اولیه</h3><p>قبل از اجرای کامل، جهت اصلی را ملموس و قابل ارزیابی می‌کنیم.</p></div></article><article><span>03</span><div><h3>اجرا، تحویل و رشد</h3><p>مرحله‌ای اجرا می‌کنیم، بازخورد می‌گیریم و سیستم قابل توسعه تحویل می‌دهیم.</p></div></article></div></div></section>

<section class="surface-section lead-section" id="contact"><div class="surface-shell lead-wrap"><div class="lead-copy"><span class="section-kicker">START A PROJECT</span><h2>پروژهٔ بعدی<br><em>می‌تواند مال شما باشد.</em></h2><p>بریف کامل لازم نیست؛ کسب‌وکار و چالش امروزتان را به زبان خودتان بنویسید.</p><div class="lead-promise"><span>قدم بعدی</span><b>بررسی و تماس اولیه</b></div></div><form class="lead-form" action="<?=url('api/order.php')?>" method="post" data-lead-form><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="budget" value="نیازمند گفتگو"><input class="hp" name="website" tabindex="-1" autocomplete="off"><fieldset><legend>مسیر مورد نیاز</legend><div class="choice-pills"><label><input type="radio" name="service" value="طراحی سایت" required><span>طراحی سایت</span></label><label><input type="radio" name="service" value="تبلیغات و کمپین"><span>تبلیغات و رشد</span></label><label><input type="radio" name="service" value="برندینگ"><span>برندینگ</span></label><label><input type="radio" name="service" value="نیاز به راهنمایی"><span>مطمئن نیستم</span></label></div></fieldset><label>دربارهٔ پروژه<textarea name="message" rows="4" minlength="10" required placeholder="کسب‌وکار، هدف و چیزی که امروز مانع شماست..."></textarea></label><div class="lead-form__row"><label>نام و نام خانوادگی<input name="name" required minlength="3" autocomplete="name" placeholder="نام شما"></label><label>شماره موبایل<input name="phone" required inputmode="tel" dir="ltr" autocomplete="tel" placeholder="0912 000 0000"></label></div><small class="form-message" role="alert"></small><button type="submit">ارسال درخواست پروژه <i>↙</i></button><small class="lead-privacy">بدون پرداخت یا تعهد اولیه؛ برای هماهنگی با شما تماس می‌گیریم.</small></form></div></section>
</main>
<?php require __DIR__ . '/../includes/surface-footer.php'; ?>
