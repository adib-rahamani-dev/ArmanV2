<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';
header('X-Robots-Tag: noindex, nofollow');
header('Location: ' . surface_url('main') . '#consultation', true, 302);
exit;
