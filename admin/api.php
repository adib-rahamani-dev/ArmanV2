<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';

if (!admin_is_authenticated()) json_response(['ok'=>false,'message'=>'نشست مدیریت منقضی شده است.'], 401);
if (!is_post()) json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'], 405);
if (!csrf_valid($_POST['csrf'] ?? null)) json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'], 419);

$action = trim((string) ($_POST['action'] ?? ''));
if ($action === 'order_status') {
    $key = preg_replace('/[^a-f0-9]/', '', (string) ($_POST['key'] ?? ''));
    $status = trim((string) ($_POST['status'] ?? ''));
    $allowed = ['new','reviewing','contacted','paid','doing','done','cancelled'];
    if (strlen($key) !== 16 || !in_array($status, $allowed, true)) json_response(['ok'=>false,'message'=>'مقدار وضعیت معتبر نیست.'], 422);
    $states = storage_json('admin/order-status.json', []);
    $states[$key] = $status;
    if (!storage_json_write('admin/order-status.json', $states)) json_response(['ok'=>false,'message'=>'ذخیره وضعیت انجام نشد.'], 500);
    json_response(['ok'=>true,'message'=>'وضعیت سفارش ذخیره شد.']);
}

if ($action === 'catalog_toggle') {
    $group = trim((string) ($_POST['group'] ?? ''));
    $id = preg_replace('/[^a-z0-9\-]/', '', strtolower((string) ($_POST['id'] ?? '')));
    $active = filter_var($_POST['active'] ?? false, FILTER_VALIDATE_BOOL);
    if (!in_array($group, ['products','accounts','courses','ads'], true) || $id === '') json_response(['ok'=>false,'message'=>'محصول معتبر نیست.'], 422);
    $catalog = require __DIR__ . '/../data/catalog.php';
    $exists = false;
    foreach (($catalog[$group] ?? []) as $item) if (($item['id'] ?? '') === $id) { $exists = true; break; }
    if (!$exists) json_response(['ok'=>false,'message'=>'محصول پیدا نشد.'], 404);
    $overrides = storage_json('catalog-overrides.json', []);
    $overrides[$group . ':' . $id] = $active;
    if (!storage_json_write('catalog-overrides.json', $overrides)) json_response(['ok'=>false,'message'=>'تغییر وضعیت ذخیره نشد.'], 500);
    json_response(['ok'=>true,'message'=>$active?'محصول منتشر شد.':'محصول از فروشگاه مخفی شد.']);
}

json_response(['ok'=>false,'message'=>'عملیات شناخته نشد.'], 404);
