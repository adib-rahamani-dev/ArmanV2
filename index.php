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
                <h1 id="hero-title">از ایده تا اجرا؛<br>یک مسیر روشن برای <em>رشد.</em></h1>
                <p class="ncc-lead">برای کسب‌وکارهایی که می‌خواهند سایت، تبلیغات و محتوایشان هدفمندتر عمل کند و مخاطب راحت‌تر تصمیم بگیرد.</p>
                <div class="ncc-actions">
                    <a class="ncc-button ncc-button--primary" href="#consultation" data-track="consultation_cta_click">درخواست مشاوره <span aria-hidden="true">←</span></a>
                    <a class="ncc-button ncc-button--quiet" href="#paths">انتخاب مسیر مناسب <span aria-hidden="true">↓</span></a>
                </div>
                <p class="ncc-trust"><span aria-hidden="true">✓</span> بررسی اولیه درخواست، بدون پرداخت و تعهد</p>
            </div>
            <div class="signal-board" aria-label="مسیر REDT از راهبرد تا رشد" role="img" data-signal>
                <div class="signal-board__meta"><span>REDT SIGNAL / 01</span><span>IDEA → IMPACT</span></div>
                <svg viewBox="0 0 640 520" aria-hidden="true" focusable="false">
                    <path class="signal-grid-line" d="M64 72H576M64 184H576M64 296H576M64 408H576M112 40V472M272 40V472M432 40V472"/>
                    <path class="signal-route" pathLength="1" d="M112 408V296H272V184H432V72H544"/>
                </svg>
                <div class="signal-node signal-node--1"><b>01</b><span>راهبرد</span></div>
                <div class="signal-node signal-node--2"><b>02</b><span>خلق</span></div>
                <div class="signal-node signal-node--3"><b>03</b><span>اجرا</span></div>
                <div class="signal-node signal-node--4"><b>04</b><span>رشد</span></div>
                <div class="signal-impact"><span>اثر</span></div>
            </div>
        </div>
    </section>

    <section class="ncc-section ncc-problem" aria-labelledby="problem-title">
        <div class="surface-shell">
            <header class="ncc-heading" data-reveal>
                <p class="ncc-index">01 / مسئله</p>
                <h2 id="problem-title">وقتی هر بخش جدا حرکت کند، نتیجه هم پراکنده می‌شود.</h2>
                <p>سایت یک پیام می‌دهد، تبلیغات پیام دیگری و محتوا مسیر متفاوتی می‌رود. REDT این بخش‌ها را هماهنگ می‌کند تا مخاطب سریع‌تر متوجه شود، اعتماد کند و اقدام انجام دهد.</p>
            </header>
            <div class="problem-map" data-reveal>
                <div class="problem-map__side" aria-label="وضعیت پراکنده"><span>پیام نامشخص</span><span>تجربه پراکنده</span><span>اقدام کم</span></div>
                <div class="problem-map__signal" aria-hidden="true"><i></i><b>REDT</b><i></i></div>
                <div class="problem-map__side problem-map__side--result" aria-label="نتیجه هماهنگ"><span>وضوح</span><span>هماهنگی</span><span>رشد</span></div>
            </div>
        </div>
    </section>

    <section class="ncc-section ncc-paths" id="paths" aria-labelledby="paths-title">
        <div class="surface-shell">
            <header class="ncc-heading ncc-heading--split" data-reveal>
                <div><p class="ncc-index">02 / راهکارها</p><h2 id="paths-title">سه مسیر؛<br>یک نقطه شروع روشن.</h2></div>
                <p>قرار نیست میان فهرستی طولانی از خدمات انتخاب کنید. مسئله را بگویید؛ اگر مسیر روشن باشد مستقیم به شاخه تخصصی می‌روید، و اگر نباشد با هم آن را پیدا می‌کنیم.</p>
            </header>
            <div class="path-lines">
                <article data-reveal>
                    <span class="path-lines__number">01</span>
                    <div><p class="ncc-label">REDT STUDIO</p><h3>مسیر استودیو</h3><p>طراحی سایت، کمپین، ویدیو و تجربه دیجیتال.</p></div>
                    <a href="<?=surface_url('studio')?>" data-track="path_studio_click">آشنایی با REDT Studio <span aria-hidden="true">←</span></a>
                </article>
                <article data-reveal>
                    <span class="path-lines__number">02</span>
                    <div><p class="ncc-label">REDT DIGITAL</p><h3>مسیر خدمات دیجیتال</h3><p>تهیه و فعال‌سازی ابزارها و سرویس‌های موردنیاز.</p></div>
                    <a href="<?=surface_url('digital')?>" data-track="path_digital_click">ورود به REDT Digital <span aria-hidden="true">←</span></a>
                </article>
                <article data-reveal>
                    <span class="path-lines__number">03</span>
                    <div><p class="ncc-label">GUIDED START</p><h3>هنوز مطمئن نیستید؟</h3><p>مسئله را توضیح دهید تا مناسب‌ترین مسیر را پیشنهاد کنیم.</p></div>
                    <a href="#consultation">انتخاب مسیر مناسب <span aria-hidden="true">←</span></a>
                </article>
            </div>
        </div>
    </section>

    <section class="ncc-section ncc-why" id="why" aria-labelledby="why-title">
        <div class="surface-shell ncc-why__layout">
            <header class="ncc-heading" data-reveal><p class="ncc-index">03 / چرا REDT</p><h2 id="why-title">فکر و اجرا، در یک گفت‌وگوی مشترک.</h2><p>کیفیت همکاری را با واژه‌های بزرگ تعریف نمی‌کنیم؛ با تصمیم‌هایی تعریف می‌کنیم که شما دلیلشان را می‌دانید.</p></header>
            <ol class="reason-list">
                <li data-reveal><span>01</span><p><b>یک مسیر یکپارچه از فکر تا اجرا</b><small>تصمیم‌های محتوا، طراحی و اجرا از یک مسئله مشترک شروع می‌شوند.</small></p></li>
                <li data-reveal><span>02</span><p><b>ارتباط مستقیم و بدون واسطه</b><small>درباره اولویت‌ها و بازخوردها روشن و بی‌پیچیدگی صحبت می‌کنیم.</small></p></li>
                <li data-reveal><span>03</span><p><b>تصمیم‌گیری بر اساس هدف کسب‌وکار</b><small>هر انتخاب باید به فهم بهتر، اعتماد بیشتر یا اقدام ساده‌تر کمک کند.</small></p></li>
                <li data-reveal><span>04</span><p><b>تجربه ساده و قابل‌فهم</b><small>کاربر برای پیدا کردن قدم بعدی نباید حدس بزند.</small></p></li>
                <li data-reveal><span>05</span><p><b>طراحی متناسب با مخاطب واقعی</b><small>راه‌حل را برای رفتار و زبان آدم‌های واقعی می‌سازیم.</small></p></li>
            </ol>
        </div>
    </section>

    <section class="ncc-section ncc-process" id="process" aria-labelledby="process-title">
        <div class="surface-shell">
            <header class="ncc-heading ncc-heading--split" data-reveal><div><p class="ncc-index">04 / روند همکاری</p><h2 id="process-title">چهار قدم، بدون نقطه مبهم.</h2></div><p>در هر مرحله می‌دانید چه چیزی بررسی می‌شود، چه تصمیمی گرفته شده و قدم بعدی چیست.</p></header>
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
                <p class="ncc-index">05 / درخواست مشاوره</p>
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
            <header class="ncc-heading" data-reveal><p class="ncc-index">06 / سؤالات متداول</p><h2 id="faq-title">قبل از شروع، چه چیزی لازم است بدانید؟</h2></header>
            <div class="faq-list">
                <?php foreach($faqs as $index=>$faq):?><details data-reveal><summary><span><?=str_pad((string)($index+1),2,'0',STR_PAD_LEFT)?></span><?=e($faq[0])?><i aria-hidden="true">+</i></summary><p><?=e($faq[1])?></p></details><?php endforeach;?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/includes/surface-footer.php'; ?>
