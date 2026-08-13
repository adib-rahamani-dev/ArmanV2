<?php
declare(strict_types=1);

require __DIR__ . '/config/config.php';
header('Content-Type: text/plain; charset=utf-8');
header('X-Robots-Tag: noindex');
?>
User-agent: *
<?php if (APP_ENV !== 'production'): ?>
Disallow: /
<?php else: ?>
Allow: /
Disallow: <?= rtrim(APP_URL, '/') ?>/admin/
Disallow: <?= rtrim(APP_URL, '/') ?>/api/
Disallow: <?= rtrim(APP_URL, '/') ?>/config/
Disallow: <?= rtrim(APP_URL, '/') ?>/data/
Disallow: <?= rtrim(APP_URL, '/') ?>/database/
Disallow: <?= rtrim(APP_URL, '/') ?>/includes/
Disallow: <?= rtrim(APP_URL, '/') ?>/storage/

Sitemap: <?= absolute_url('sitemap.php') ?>
<?php endif; ?>
