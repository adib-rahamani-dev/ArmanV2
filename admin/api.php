<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';

if (!admin_is_authenticated()) json_response(['ok'=>false,'message'=>'نشست مدیریت منقضی شده است.'], 401);
if (!is_post()) json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'], 405);
if (!csrf_valid($_POST['csrf'] ?? null)) json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'], 419);

$action = trim((string) ($_POST['action'] ?? ''));
if ($action === 'order_status') {
    $key = preg_replace('/[^a-zA-Z0-9_]/', '', (string) ($_POST['key'] ?? ''));
    $status = trim((string) ($_POST['status'] ?? ''));
    $allowed = ['new','awaiting_payment','verification_review','reviewing','contacted','verified','rejected','paid','doing','done','cancelled'];
    if ($key === '' || !in_array($status, $allowed, true)) json_response(['ok'=>false,'message'=>'مقدار وضعیت معتبر نیست.'], 422);
    if (str_starts_with($key, 'ord_')) {
        try {
            $pdo=db(); $pdo->beginTransaction();
            $current=$pdo->prepare('SELECT status FROM orders WHERE id=?'); $current->execute([$key]); $previous=$current->fetchColumn();
            if(!is_string($previous)) json_response(['ok'=>false,'message'=>'سفارش پیدا نشد.'],404);
            $identityStatus=in_array($status,['verified','rejected'],true)?$status:null;
            if($identityStatus)$pdo->prepare('UPDATE orders SET status=?,identity_status=?,updated_at=? WHERE id=?')->execute([$status,$identityStatus,db_now(),$key]);
            else $pdo->prepare('UPDATE orders SET status=?,updated_at=? WHERE id=?')->execute([$status,db_now(),$key]);
            if($identityStatus)$pdo->prepare('UPDATE verifications SET status=?,reviewed_at=?,updated_at=? WHERE order_id=?')->execute([$identityStatus,db_now(),db_now(),$key]);
            $pdo->prepare('INSERT INTO order_events (id,order_id,event_type,previous_status,new_status,actor,metadata,created_at) VALUES (?,?,?,?,?,?,?,?)')->execute([db_id('evt_'),$key,'status_changed',$previous,$status,'admin',db_json([]),db_now()]);
            $pdo->commit();
            json_response(['ok'=>true,'message'=>'وضعیت سفارش ذخیره شد.']);
        } catch(Throwable $error) { if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack(); json_response(['ok'=>false,'message'=>'ذخیره وضعیت انجام نشد.'],500); }
    }
    if (strlen($key) !== 16) json_response(['ok'=>false,'message'=>'شناسه سفارش معتبر نیست.'],422);
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
    try {
        $pdo=db(); $driver=$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sql=$driver==='pgsql'
            ? 'INSERT INTO product_overrides (group_name,product_id,active,updated_at) VALUES (?,?,?,?) ON CONFLICT (group_name,product_id) DO UPDATE SET active=EXCLUDED.active,updated_at=EXCLUDED.updated_at'
            : 'INSERT INTO product_overrides (group_name,product_id,active,updated_at) VALUES (?,?,?,?) ON CONFLICT(group_name,product_id) DO UPDATE SET active=excluded.active,updated_at=excluded.updated_at';
        $pdo->prepare($sql)->execute([$group,$id,$active,db_now()]);
    } catch(Throwable) {
        $overrides = storage_json('catalog-overrides.json', []); $overrides[$group . ':' . $id] = $active;
        if (!storage_json_write('catalog-overrides.json', $overrides)) json_response(['ok'=>false,'message'=>'تغییر وضعیت ذخیره نشد.'], 500);
    }
    json_response(['ok'=>true,'message'=>$active?'محصول منتشر شد.':'محصول از فروشگاه مخفی شد.']);
}

json_response(['ok'=>false,'message'=>'عملیات شناخته نشد.'], 404);
