<?php
declare(strict_types=1);

require __DIR__ . '/config/config.php';
$routes = require __DIR__ . '/data/routes.php';
$lastModified = gmdate('Y-m-d', (int) max(
    filemtime(__DIR__ . '/index.php') ?: time(),
    filemtime(__DIR__ . '/includes/conversion.php') ?: time()
));

header('Content-Type: application/xml; charset=utf-8');
header('X-Content-Type-Options: nosniff');
echo '<?xml version="1.0" encoding="UTF-8"?>', PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($routes as $route): ?>
<?php if (($route['indexable'] ?? false) !== true) continue; ?>
  <url>
    <loc><?= htmlspecialchars(absolute_url((string) $route['path']), ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></loc>
    <lastmod><?= $lastModified ?></lastmod>
    <changefreq><?= htmlspecialchars((string) ($route['changefreq'] ?? 'monthly'), ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></changefreq>
    <priority><?= htmlspecialchars((string) ($route['priority'] ?? '0.5'), ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
