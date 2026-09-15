<?php
declare(strict_types=1);

function database_url_to_dsn(string $url): array
{
    $parts = parse_url($url);
    if (!is_array($parts) || empty($parts['host']) || empty($parts['path'])) {
        throw new RuntimeException('DATABASE_URL معتبر نیست.');
    }
    $query = [];
    parse_str((string) ($parts['query'] ?? ''), $query);
    $dsn = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
        $parts['host'],
        (int) ($parts['port'] ?? 5432),
        ltrim((string) $parts['path'], '/'),
        preg_replace('/[^a-z]/', '', (string) ($query['sslmode'] ?? 'require')) ?: 'require'
    );
    if (str_ends_with((string) $parts['host'], '.neon.tech')) {
        $endpointId = explode('.', (string) $parts['host'])[0];
        $dsn .= ";options='endpoint=" . preg_replace('/[^a-zA-Z0-9-]/', '', $endpointId) . "'";
    }
    return [$dsn, rawurldecode((string) ($parts['user'] ?? '')), rawurldecode((string) ($parts['pass'] ?? ''))];
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $databaseUrl = trim((string) (getenv('DATABASE_URL') ?: getenv('POSTGRES_URL') ?: ''));
    if ($databaseUrl !== '') {
        [$dsn, $user, $pass] = database_url_to_dsn($databaseUrl);
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    }

    if (APP_ENV === 'production') {
        throw new RuntimeException('دیتابیس تولید هنوز متصل نشده است.');
    }
    $directory = STORAGE_PATH . '/database';
    if (!is_dir($directory)) mkdir($directory, 0755, true);
    $path = $directory . '/redt.sqlite';
    $firstRun = !is_file($path);
    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');
    if ($firstRun) {
        $schema = file_get_contents(dirname(__DIR__) . '/database/schema-sqlite.sql');
        if (!is_string($schema)) throw new RuntimeException('فایل ساخت دیتابیس محلی پیدا نشد.');
        $pdo->exec($schema);
    }
    return $pdo;
}

function db_available(): bool
{
    try { db()->query('SELECT 1'); return true; }
    catch (Throwable) { return false; }
}

function db_id(string $prefix = ''): string
{
    return $prefix . bin2hex(random_bytes(16));
}

function db_now(): string
{
    return gmdate('Y-m-d H:i:sP');
}

function db_json(array $data): string
{
    return (string) json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
