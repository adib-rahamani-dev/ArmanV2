<?php
declare(strict_types=1);
require __DIR__ . '/config/config.php';

$surface = 'main';
$pageTitle = 'REDT | شریک خلاق رشد؛ از ایده تا اجرا';
$pageDescription = 'REDT کمک می‌کند سایت، تبلیغات و محتوای کسب‌وکار شما در یک مسیر روشن حرکت کنند؛ برای انتخاب نقطه شروع، درخواست مشاوره ثبت کنید.';
$faqs = [
    ['جلسه اولیه چگونه برگزار می‌شود؟','پس از بررسی درخواست، برای هماهنگی یک گفت‌وگوی کوتاه تلفنی یا آنلاین با شما انجام می‌شود. این گفت‌وگو برای روشن‌شدن مسئله است و پرداخت یا تعهدی ندارد.'],
    ['برای شروع چه اطلاعاتی لازم است؟','همین‌قدر که کسب‌وکارتان چیست، چه چیزی خوب پیش نمی‌رود و دوست دارید چه نتیجه‌ای تغییر کند کافی است. بریف یا اطلاعات فنی کامل لازم نیست.'],
    ['اگر ندانم کدام خدمت مناسب است چه می‌شود؟','گزینه «هنوز مطمئن نیستم» را انتخاب کنید. ابتدا مسئله را می‌شنویم و سپس مناسب‌ترین مسیر را پیشنهاد می‌کنیم.'],
    ['زمان و هزینه پروژه چگونه مشخص می‌شود؟','بعد از شناخت دامنه کار، خروجی‌ها و محدودیت‌ها، زمان‌بندی و برآورد هزینه به‌صورت شفاف ارائه می‌شود.'],
    ['آیا امکان همکاری مرحله‌ای وجود دارد؟','بله. اگر منطقی باشد، می‌توان پروژه را به مرحله‌های مشخص تقسیم کرد تا هر بخش پیش از ادامه ارزیابی شود.'],
    ['پس از ارسال درخواست چه اتفاقی می‌افتد؟','درخواست بررسی می‌شود و حداکثر تا یک روز کاری برای هماهنگی قدم بعدی با شما تماس می‌گیریم.'],
];
$pageSchema = [[
    '@type'=>'FAQPage',
    'mainEntity'=>array_map(static fn(array $faq): array => [
        '@type'=>'Question','name'=>$faq[0],
        'acceptedAnswer'=>['@type'=>'Answer','text'=>$faq[1]],
    ], $faqs),
]];
require __DIR__ . '/includes/surface-header.php';
?>
<main id="start" class="ncc-main">
    <section class="ncc-hero" aria-labelledby="hero-title">
        <div class="surface-shell ncc-hero__layout">
            <div class="ncc-hero__copy" data-reveal>
                <p class="ncc-eyebrow"><span aria-hidden="true"></span> CREATIVE GROWTH PARTNER</p>
                <h1 id="hero-title">تجربه‌ای می‌سازیم که مخاطب <em>به خاطر می‌سپارد.</em></h1>
                <p class="ncc-lead">استراتژی، طراحی و اجرا را کنار هم می‌گذاریم تا برند شما حرفه‌ای‌تر دیده شود و مخاطب راحت‌تر اعتماد کند.</p>
                <div class="ncc-actions">
                    <a class="ncc-button ncc-button--primary" href="#consultation" data-track="consultation_cta_click">درخواست مشاوره <span aria-hidden="true">←</span></a>
                    <a class="ncc-button ncc-button--quiet" href="#work">دیدن نمونه‌کارها <span aria-hidden="true">↓</span></a>
                </div>
                <p class="ncc-trust">طراحی وب · کمپین · هویت بصری · محصول دیجیتال</p>
            </div>
            <figure class="ncc-hero__media" data-reveal>
                <img src="<?=asset_url('assets/images/generated/hero-studio-demo.jpg')?>" width="1680" height="945" alt="فضای کاری مینیمال با لپ‌تاپ و عناصر طراحی" fetchpriority="high">
                <figcaption><span>REDT / CREATIVE STUDIO</span><b>Strategy → Design → Growth</b></figcaption>
            </figure>
        </div>
    </section>

    <div class="ncc-proofbar" aria-label="ویژگی‌های همکاری">
        <div class="surface-shell">
            <span><b>01</b> طراحی بر پایه هدف</span>
            <span><b>02</b> همکاری مستقیم</span>
            <span><b>03</b> اجرای مرحله‌ای و شفاف</span>
        </div>
    </div>

    <section class="ncc-section ncc-paths" id="paths" aria-labelledby="paths-title">
        <div class="surface-shell">
            <header class="ncc-heading ncc-heading--split" data-reveal>
                <div><p class="ncc-index">01 / خدمات</p><h2 id="paths-title">کمتر، دقیق‌تر،<br>اثرگذارتر.</h2></div>
                <p>از فهرست‌های طولانی خبری نیست. مسئله را می‌شناسیم و فقط همان چیزی را می‌سازیم که به دیده‌شدن و رشد کسب‌وکار کمک می‌کند.</p>
            </header>
            <div class="path-lines">
                <article data-reveal>
                    <span class="path-lines__number">01</span>
                    <div><p class="ncc-label">WEB & EXPERIENCE</p><h3>طراحی سایت و تجربه دیجیتال</h3><p>سایت‌هایی روشن، سریع و متقاعدکننده که کاربر را به قدم بعدی هدایت می‌کنند.</p></div>
                    <a href="<?=surface_url('studio')?>" data-track="path_studio_click">مشاهده خدمات <span aria-hidden="true">←</span></a>
                </article>
                <article data-reveal>
                    <span class="path-lines__number">02</span>
                    <div><p class="ncc-label">BRAND & CAMPAIGN</p><h3>هویت بصری و کمپین</h3><p>یک زبان منسجم برای برند؛ از تصویر و محتوا تا کمپینی که دیده می‌شود.</p></div>
                    <a href="<?=surface_url('studio')?>#services">مشاهده خدمات <span aria-hidden="true">←</span></a>
                </article>
                <article data-reveal>
                    <span class="path-lines__number">03</span>
                    <div><p class="ncc-label">DIGITAL PRODUCTS</p><h3>محصول و خدمات دیجیتال</h3><p>راه‌اندازی و تهیه امن ابزارها و سرویس‌هایی که تیم شما به آن‌ها نیاز دارد.</p></div>
                    <a href="<?=surface_url('digital')?>" data-track="path_digital_click">ورود به Digital <span aria-hidden="true">←</span></a>
                </article>
            </div>
        </div>
    </section>

    <section class="ncc-section ncc-work" id="work" aria-labelledby="work-title">
        <div class="surface-shell">
            <header class="ncc-heading ncc-heading--split" data-reveal>
                <div><p class="ncc-index">02 / نمونه‌کار</p><h2 id="work-title">تصویر، قبل از توضیح<br>اعتماد می‌سازد.</h2></div>
                <p>این تصاویر فعلاً کانسپت نمایشی‌اند و بعداً با پروژه‌های واقعی شما جایگزین می‌شوند.</p>
            </header>
            <div class="ncc-work__grid">
                <figure class="ncc-work__item ncc-work__item--wide" data-reveal>
                    <img src="<?=asset_url('assets/images/case-studies/architecture-v2.webp')?>" width="1456" height="1092" alt="نمایش کانسپت وب‌سایت معماری روی مانیتور و تبلت" loading="lazy">
                    <figcaption><span>وب‌سایت معماری</span><small>طراحی تجربه و رابط کاربری</small><i>کانسپت نمایشی</i></figcaption>
                </figure>
                <figure class="ncc-work__item" data-reveal>
                    <img src="<?=asset_url('assets/images/case-studies/coffee-v2.webp')?>" width="1456" height="1092" alt="کانسپت بسته‌بندی مینیمال قهوه" loading="lazy">
                    <figcaption><span>برند قهوه</span><small>هویت بصری و بسته‌بندی</small><i>کانسپت نمایشی</i></figcaption>
                </figure>
                <figure class="ncc-work__item" data-reveal>
                    <img src="<?=asset_url('assets/images/case-studies/fintech-v2.webp')?>" width="1456" height="1092" alt="کانسپت رابط کاربری محصول فین‌تک روی موبایل" loading="lazy">
                    <figcaption><span>محصول فین‌تک</span><small>طراحی محصول و رابط کاربری</small><i>کانسپت نمایشی</i></figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="ncc-section ncc-process" id="process" aria-labelledby="process-title">
        <div class="surface-shell">
            <header class="ncc-heading ncc-heading--split" data-reveal><div><p class="ncc-index">03 / روند همکاری</p><h2 id="process-title">ساده، شفاف،<br>بدون رفت‌وبرگشت اضافه.</h2></div><p>در هر مرحله می‌دانید چه چیزی بررسی می‌شود، چه تصمیمی گرفته شده و قدم بعدی چیست.</p></header>
            <ol class="signal-process" data-signal-process>
                <li data-reveal><span>01</span><h3>شناخت مسئله</h3><p>کسب‌وکار، مخاطب، هدف و مانع امروز را روشن می‌کنیم.</p></li>
                <li data-reveal><span>02</span><h3>انتخاب مسیر</h3><p>اولویت، دامنه همکاری و نقطه شروع مناسب مشخص می‌شود.</p></li>
                <li data-reveal><span>03</span><h3>طراحی و اجرا</h3><p>راه‌حل مرحله‌ای ساخته می‌شود تا تصمیم‌ها قابل ارزیابی بمانند.</p></li>
                <li data-reveal><span>04</span><h3>ارزیابی و بهبود</h3><p>خروجی را با هدف اولیه می‌سنجیم و برای ادامه آماده می‌کنیم.</p></li>
            </ol>
        </div>
    </section>

    <section class="ncc-section ncc-consultation" id="consultation" aria-labelledby="consultation-title">
        <div class="surface-shell consultation-layout">
            <div class="consultation-copy" data-reveal>
                <p class="ncc-index">04 / درخواست مشاوره</p>
                <h2 id="consultation-title">مسئله را بگویید؛ مسیر مناسب را با هم پیدا می‌کنیم.</h2>
                <p>لازم نیست بریف کامل یا اطلاعات فنی آماده داشته باشید. در چند خط توضیح دهید چه چیزی می‌خواهید بهتر شود.</p>
                <div class="consultation-assurance"><span aria-hidden="true">↳</span><p><b>پاسخ حداکثر تا یک روز کاری</b><small>بررسی اولیه رایگان است و تعهدی برای شروع پروژه ایجاد نمی‌کند.</small></p></div>
            </div>
            <form class="consultation-form" action="<?=url('api/consultation.php')?>" method="post" data-consultation-form novalidate>
                <input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
                <input class="hp" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                <div class="form-signal" aria-label="مرحله فرم"><span class="is-active">۱</span><i></i><span>۲</span><i></i><span>۳</span><b data-step-label>موضوع درخواست</b></div>
                <fieldset class="form-step is-active" data-form-step="0">
                    <legend>برای چه موضوعی کمک می‌خواهید؟</legend>
                    <div class="form-options">
                        <?php foreach(['سایت و تجربه دیجیتال','تبلیغات و کمپین','ویدیو و محتوا','خدمات دیجیتال','هنوز مطمئن نیستم'] as $option):?>
                        <label><input type="radio" name="service" value="<?=e($option)?>" required><span><?=e($option)?><i aria-hidden="true">←</i></span></label>
                        <?php endforeach;?>
                    </div>
                    <p class="field-error" data-error role="alert"></p>
                    <button class="ncc-button ncc-button--dark form-next" type="button">ادامه <span aria-hidden="true">←</span></button>
                </fieldset>
                <fieldset class="form-step" data-form-step="1">
                    <legend>کمی درباره هدف یا مشکل بگویید.</legend>
                    <label class="form-field">توضیح هدف یا مشکل<textarea name="message" rows="5" minlength="10" required placeholder="مثلاً سایت فعلی ما درخواست کمی دریافت می‌کند و پیام اصلی روشن نیست."></textarea></label>
                    <div class="form-grid-2">
                        <label class="form-field">بازه زمانی<select name="timeline"><option value="">انتخاب کنید</option><option>کمتر از یک ماه</option><option>۱ تا ۳ ماه</option><option>بیشتر از ۳ ماه</option><option>هنوز مشخص نیست</option></select></label>
                        <label class="form-field">بازه بودجه <small>اختیاری</small><select name="budget"><option value="">ترجیح می‌دهم بعداً بگویم</option><option>کمتر از ۵۰ میلیون تومان</option><option>۵۰ تا ۱۵۰ میلیون تومان</option><option>بیشتر از ۱۵۰ میلیون تومان</option></select></label>
                    </div>
                    <p class="field-error" data-error role="alert"></p>
                    <div class="form-controls"><button class="form-back" type="button">بازگشت</button><button class="ncc-button ncc-button--dark form-next" type="button">ادامه <span aria-hidden="true">←</span></button></div>
                </fieldset>
                <fieldset class="form-step" data-form-step="2">
                    <legend>برای هماهنگی چطور با شما تماس بگیریم؟</legend>
                    <div class="form-grid-2"><label class="form-field">نام و نام خانوادگی<input name="name" autocomplete="name" minlength="3" required placeholder="نام شما"></label><label class="form-field">شماره موبایل<input name="phone" type="tel" inputmode="numeric" autocomplete="tel" dir="ltr" required placeholder="0912 000 0000"></label></div>
                    <fieldset class="contact-method"><legend>روش ترجیحی ارتباط</legend><div><label><input type="radio" name="contact_method" value="تماس تلفنی" checked><span>تماس تلفنی</span></label><label><input type="radio" name="contact_method" value="پیامک"><span>پیامک</span></label><label><input type="radio" name="contact_method" value="واتساپ"><span>واتساپ</span></label></div></fieldset>
                    <p class="field-error" data-error role="alert"></p>
                    <div class="form-controls"><button class="form-back" type="button">بازگشت</button><button class="ncc-button ncc-button--dark form-submit" type="submit">ارسال درخواست مشاوره <span aria-hidden="true">←</span></button></div>
                    <p class="form-privacy">با ارسال فرم، فقط برای بررسی همین درخواست با شما تماس می‌گیریم. <a href="<?=url('privacy')?>">حریم خصوصی</a></p>
                </fieldset>
                <div class="form-success" data-form-success hidden role="status" aria-live="polite"><span aria-hidden="true">✓</span><h3>درخواست شما ثبت شد.</h3><p>حداکثر تا یک روز کاری برای هماهنگی با شما تماس می‌گیریم.</p></div>
            </form>
        </div>
    </section>

    <section class="ncc-section ncc-faq" id="faq" aria-labelledby="faq-title">
        <div class="surface-shell faq-layout">
            <header class="ncc-heading" data-reveal><p class="ncc-index">05 / سؤالات متداول</p><h2 id="faq-title">قبل از شروع، چه چیزی لازم است بدانید؟</h2></header>
            <div class="faq-list">
                <?php foreach($faqs as $index=>$faq):?><details data-reveal><summary><span><?=str_pad((string)($index+1),2,'0',STR_PAD_LEFT)?></span><?=e($faq[0])?><i aria-hidden="true">+</i></summary><p><?=e($faq[1])?></p></details><?php endforeach;?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/surface-footer.php'; ?>
