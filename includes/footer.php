<footer class="footer footer--levelup">
    <div class="footer__signal" aria-hidden="true"><span>REDT® — THINK / DESIGN / BUILD — </span><span>REDT® — THINK / DESIGN / BUILD — </span></div>
    <div class="container footer-stage">
        <div class="footer-intro">
            <a class="logo logo--footer" href="<?= url() ?>" aria-label="REDT؛ صفحه اصلی"><b>RED</b>T</a>
            <h2>ایده خوب دارید؟<br><em>بیایید واقعی‌اش کنیم.</em></h2>
            <a class="footer-start" href="<?= url('#contact') ?>">شروع یک گفتگو <i>↙</i></a>
        </div>
        <div class="footer-directory">
            <div><span>پلتفرم</span><a href="<?=url('page.php?type=products')?>">فروشگاه</a><a href="<?=url('page.php?type=accounts')?>">اکانت‌ها</a><a href="<?=url('page.php?type=ads')?>">سفارش تبلیغات</a><a href="<?=url('page.php?type=courses')?>">دوره‌ها</a><a href="<?=url('user/')?>">حساب کاربری</a></div>
            <div><span>استودیو</span><a href="<?=url('page.php?type=portfolio')?>">نمونه‌کارها</a><a href="<?=url('page.php?type=about')?>">درباره ما</a><a href="<?=url('page.php?type=blog')?>">مقالات</a><a href="<?=url('#services')?>">خدمات</a></div>
            <div><span>ارتباط</span><a dir="ltr" href="tel:+982188765432">+98 21 8876 5432</a><a href="mailto:hello@redt.ir">hello@redt.ir</a><span class="footer-address">تهران، ایران</span></div>
        </div>
        <div class="footer-clock">
            <span>LOCAL TIME / TEHRAN</span>
            <time data-tehran-clock datetime="">--:--:--</time>
            <small>Asia / Tehran · GMT +3:30</small>
        </div>
    </div>
    <div class="container footer__bottom"><span>© <?= date('Y') ?> REDT. همه حقوق محفوظ است.</span><span class="footer__status"><i></i> آماده پذیرش پروژه جدید</span><a href="<?=url('page.php?type=privacy')?>">حریم خصوصی و امنیت</a></div>
</footer>
<div class="toast" role="status" aria-live="polite"></div>
<script src="<?=asset_url('assets/js/app.js')?>" defer></script>
<script src="<?=asset_url('assets/js/site-levelup.js')?>" defer></script>
</body></html>
