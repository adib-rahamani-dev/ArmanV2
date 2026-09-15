<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';

if (!is_post()) json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'], 405);
if (!csrf_valid($_POST['csrf'] ?? null)) json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'], 419);
if (!empty($_POST['website'])) json_response(['ok'=>true,'message'=>'سفارش ثبت شد.']);
if (time() - (int) ($_SESSION['last_purchase'] ?? 0) < 15) json_response(['ok'=>false,'message'=>'لطفاً چند ثانیه بعد دوباره تلاش کنید.'], 429);

$productId = preg_replace('/[^a-z0-9-]/', '', strtolower((string) ($_POST['product_id'] ?? '')));
$plan = mb_substr(trim((string) ($_POST['plan'] ?? '')), 0, 120);
$name = mb_substr(trim((string) ($_POST['name'] ?? '')), 0, 120);
$phone = preg_replace('/[^0-9+]/', '', (string) ($_POST['phone'] ?? ''));
$activationEmail = mb_substr(trim((string) ($_POST['account'] ?? '')), 0, 190);
$notes = mb_substr(trim((string) ($_POST['notes'] ?? '')), 0, 1200);

$catalog = require __DIR__ . '/../data/catalog.php';
$product = null;
foreach (['products','accounts','courses','ads'] as $group) foreach (($catalog[$group] ?? []) as $candidate) {
    if (($candidate['id'] ?? '') === $productId && ($candidate['active'] ?? true)) { $product=$candidate; $product['_group']=$group; break 2; }
}
if (!$product) json_response(['ok'=>false,'message'=>'این محصول فعلاً قابل سفارش نیست.'], 404);
if (mb_strlen($name) < 3 || !preg_match('/^(?:\+98|0)?9\d{9}$/', $phone)) json_response(['ok'=>false,'message'=>'نام و شماره موبایل معتبر وارد کنید.'], 422);
if ($activationEmail !== '' && !filter_var($activationEmail, FILTER_VALIDATE_EMAIL)) json_response(['ok'=>false,'message'=>'ایمیل را درست وارد کنید یا خالی بگذارید.'], 422);

$orderId = db_id('ord_');
$customerId = db_id('cus_');
$trackingCode = 'RDT-' . strtoupper(substr(bin2hex(random_bytes(6)), 0, 10));
$verificationToken = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
$now = db_now();

try {
    $pdo = db();
    $pdo->beginTransaction();
    $select = $pdo->prepare('SELECT id FROM customers WHERE phone = ?');
    $select->execute([$phone]);
    $existing = $select->fetchColumn();
    if (is_string($existing) && $existing !== '') {
        $customerId = $existing;
        $pdo->prepare('UPDATE customers SET full_name=?, email=?, updated_at=? WHERE id=?')->execute([$name, $activationEmail ?: null, $now, $customerId]);
    } else {
        $pdo->prepare('INSERT INTO customers (id,full_name,phone,email,created_at,updated_at) VALUES (?,?,?,?,?,?)')->execute([$customerId,$name,$phone,$activationEmail ?: null,$now,$now]);
    }
    $pdo->prepare('INSERT INTO orders (id,tracking_code,customer_id,source,product_id,product_title,group_name,plan,price_snapshot,activation_email,notes,status,payment_status,identity_status,verification_token_hash,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
        ->execute([$orderId,$trackingCode,$customerId,'purchase',$productId,(string)$product['title'],$product['_group'],$plan,(string)($product['price']??''),$activationEmail ?: null,$notes,'awaiting_payment','not_submitted','not_submitted',hash('sha256',$verificationToken),$now,$now]);
    $pdo->prepare('INSERT INTO order_events (id,order_id,event_type,previous_status,new_status,actor,metadata,created_at) VALUES (?,?,?,?,?,?,?,?)')
        ->execute([db_id('evt_'),$orderId,'order_created',null,'awaiting_payment','customer',db_json(['product_id'=>$productId]),$now]);
    $pdo->commit();
} catch (Throwable $error) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log('REDT purchase database error: ' . $error->getMessage());
    json_response(['ok'=>false,'message'=>'اتصال ثبت سفارش آماده نیست؛ لطفاً چند دقیقه دیگر تلاش کنید.'], 503);
}

$_SESSION['last_purchase'] = time();
json_response(['ok'=>true,'message'=>'درخواست شما ثبت شد.','order_id'=>$trackingCode,'verification_url'=>url('verify.php?token=' . rawurlencode($verificationToken))]);
