<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';
$surface = 'arman';
$pageTitle = 'آرمان رجایی | رویدادها، نوشته‌ها و اتفاق‌های تازه';
$pageDescription = 'فضای شخصی و دوستانه آرمان رجایی برای دنبال‌کردن رویدادها، دریافت بلیت، نوشته‌ها و فعالیت‌های جامعه.';
$pageSchema = [['@type'=>'Person','name'=>'آرمان رجایی','url'=>surface_url('arman')]];
require __DIR__ . '/../includes/surface-header.php';
?>
<main id="start">
    <section class="community-hero">
        <div class="surface-shell community-hero__grid">
            <div class="community-hero__copy" data-reveal>
                <p class="ncc-eyebrow"><span aria-hidden="true"></span> ARMAN RAJAEI / COMMUNITY</p>
                <h1>اینجا از اتفاق‌های تازه <em>باخبر می‌شوید.</em></h1>
                <p>رویدادها، نوشته‌ها و فعالیت‌هایی که می‌توانید ساده و مستقیم در آن‌ها شرکت کنید؛ بدون فرم‌های پیچیده و حساب کاربری اجباری.</p>
                <div class="ncc-actions"><a class="ncc-button ncc-button--primary" href="#events">مشاهده رویدادها <span aria-hidden="true">↓</span></a><a class="ncc-button ncc-button--quiet" href="#about">درباره آرمان</a></div>
            </div>
            <div class="community-signal" role="img" aria-label="مسیر ارتباط میان رویدادها، نوشته‌ها و جامعه">
                <i aria-hidden="true"></i><span>رویدادها</span><span>نوشته‌ها</span><span>باهم بودن</span>
            </div>
        </div>
    </section>

    <section class="ncc-section" id="events" aria-labelledby="events-title">
        <div class="surface-shell">
            <header class="ncc-heading" data-reveal><p class="ncc-index">01 / رویدادها</p><h2 id="events-title">رویداد فعال</h2><p>به‌محض آغاز ثبت‌نام، وضعیت ظرفیت، زمان، مکان و دکمه دریافت بلیت همین‌جا نمایش داده می‌شود.</p></header>
            <article class="event-empty" data-reveal>
                <div><span class="event-empty__status">فعلاً رویداد فعالی نداریم</span><h3>اتفاق بعدی در حال آماده‌شدن است.</h3><p>تا زمان تأیید اطلاعات واقعی رویداد، بلیت یا ظرفیت ساختگی نمایش نمی‌دهیم.</p></div>
                <div class="event-empty__badge" aria-hidden="true">EVENT<br>STATUS</div>
            </article>
        </div>
    </section>

    <section class="ncc-section ncc-problem" id="articles" aria-labelledby="articles-title">
        <div class="surface-shell">
            <header class="ncc-heading" data-reveal><p class="ncc-index">02 / نوشته‌ها</p><h2 id="articles-title">آخرین نوشته‌ها و خبرها</h2><p>این بخش پس از تأیید محتوای واقعی، با چیدمان خوانا و تاریخ انتشار دقیق تکمیل می‌شود.</p></header>
            <div class="community-feed">
                <article data-reveal><span>نوشته‌ها</span><h3>جایی برای یادداشت‌های تازه</h3><p>مقاله‌ها با عنوان روشن، زمان مطالعه و ساختار مناسب خواندن روی موبایل منتشر می‌شوند.</p></article>
                <article data-reveal><span>اعلان‌ها</span><h3>خبر مهم، جلوی چشم</h3><p>اعلان‌های واقعی و زمان‌دار بدون بنرهای مزاحم در صفحه اول دیده می‌شوند.</p></article>
                <article data-reveal><span>فعالیت‌ها</span><h3>شرکت‌کردن بدون پیچیدگی</h3><p>قوانین، شرایط حضور و نتیجه هر فعالیت پیش از اقدام به زبان ساده توضیح داده می‌شود.</p></article>
            </div>
        </div>
    </section>

    <section class="ncc-section" id="about" aria-labelledby="about-title">
        <div class="surface-shell ncc-why__layout">
            <header class="ncc-heading" data-reveal><p class="ncc-index">03 / درباره آرمان</p><h2 id="about-title">یک فضای انسانی برای ارتباط مستقیم‌تر.</h2></header>
            <div class="community-about-copy" data-reveal><p>شرح‌حال، افتخارات و سوابق فقط پس از دریافت اطلاعات مستند از آرمان رجایی منتشر می‌شوند. این صفحه آماده است تا آن روایت واقعی را بدون اغراق و با ترتیب زمانی روشن نمایش دهد.</p></div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/../includes/surface-footer.php'; ?>
