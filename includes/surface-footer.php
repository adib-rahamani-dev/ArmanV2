<?php
declare(strict_types=1);
$dockTarget = $surface === 'digital' ? '#catalog' : '#contact';
$dockLabel = $surface === 'digital' ? 'سفارش' : 'درخواست';
?>
<footer class="surface-footer">
    <div class="surface-shell surface-footer__top">
        <div class="surface-footer__statement">
            <a class="surface-logo" href="<?=surface_url('main')?>"><span><b>RED</b>T</span><small>NETWORK</small></a>
            <h2>یک مسئله دارید؟<br><em>بیایید ساده‌اش کنیم.</em></h2>
        </div>
        <div class="surface-footer__links">
            <nav><span>فضاها</span><a href="<?=surface_url('main')?>">لندینگ و مشاوره</a><a href="<?=surface_url('studio')?>">استودیو و پروژه‌ها</a><a href="<?=surface_url('digital')?>">فروشگاه دیجیتال</a></nav>
            <nav><span>ارتباط</span><a dir="ltr" href="tel:+982188765432">+98 21 8876 5432</a><a href="mailto:hello@redt.ir">hello@redt.ir</a><a href="<?=url('page.php?type=privacy')?>">حریم خصوصی</a></nav>
        </div>
    </div>
    <div class="surface-shell surface-footer__bottom"><span>© <?=date('Y')?> REDT</span><span class="availability"><i></i> پذیرش پروژه و درخواست جدید</span><span>TEHRAN / IRAN</span></div>
</footer>
<nav class="app-dock" aria-label="ناوبری سریع موبایل">
    <a href="<?=surface_url('main')?>" class="<?=$surface==='main'?'active':''?>"><i>⌂</i><span>شروع</span></a>
    <a href="<?=surface_url('studio')?>" class="<?=$surface==='studio'?'active':''?>"><i>◇</i><span>استودیو</span></a>
    <a href="<?=surface_url('digital')?>" class="<?=$surface==='digital'?'active':''?>"><i>▦</i><span>دیجیتال</span></a>
    <a href="<?=e($dockTarget)?>" class="dock-action"><i>＋</i><span><?=e($dockLabel)?></span></a>
</nav>
<div class="surface-toast" role="status" aria-live="polite"></div>
<script src="<?=asset_url('assets/js/surfaces.js')?>" defer></script>
</body>
</html>
