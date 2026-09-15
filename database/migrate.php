<?php
declare(strict_types=1);
require dirname(__DIR__) . '/config/config.php';
$databaseUrl = trim((string) (getenv('DATABASE_URL') ?: getenv('POSTGRES_URL') ?: ''));
$file = $databaseUrl !== '' ? __DIR__ . '/schema-postgres.sql' : __DIR__ . '/schema-sqlite.sql';
$sql = file_get_contents($file);
if (!is_string($sql)) throw new RuntimeException('Schema unavailable.');
db()->exec($sql);
fwrite(STDOUT, "Database schema is up to date.\n");
