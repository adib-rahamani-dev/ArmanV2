<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';

if (!is_post()) json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'], 405);
if (!csrf_valid($_POST['csrf'] ?? null)) json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'], 419);
$token = trim((string) ($_POST['token'] ?? ''));
$nationalId = preg_replace('/\D/', '', (string) ($_POST['national_id'] ?? ''));
$cardLast4 = preg_replace('/\D/', '', (string) ($_POST['card_last4'] ?? ''));
$consent = ($_POST['consent'] ?? '') === '1';
if (strlen($token) < 30 || strlen($nationalId) !== 10 || strlen($cardLast4) !== 4 || !$consent) json_response(['ok'=>false,'message'=>'کد ملی، چهار رقم آخر کارت و تأیید رضایت را کامل کنید.'], 422);

try {
    $pdo = db();
    $statement = $pdo->prepare('SELECT id, tracking_code, status FROM orders WHERE verification_token_hash=?');
    $statement->execute([hash('sha256', $token)]);
    $order = $statement->fetch();
    if (!is_array($order)) json_response(['ok'=>false,'message'=>'لینک احراز هویت معتبر نیست.'], 404);
    $already = $pdo->prepare('SELECT id FROM verifications WHERE order_id=?'); $already->execute([$order['id']]);
    if ($already->fetchColumn()) json_response(['ok'=>false,'message'=>'مدارک این سفارش قبلاً ارسال شده است.'], 409);
    $receipt = private_blob_upload(validate_private_image($_FILES['receipt'] ?? [], 'فیش واریزی'), (string)$order['tracking_code'], 'receipt');
    $bankCard = private_blob_upload(validate_private_image($_FILES['bank_card'] ?? [], 'تصویر کارت پرداخت'), (string)$order['tracking_code'], 'bank-card');
    $nationalCard = private_blob_upload(validate_private_image($_FILES['national_card'] ?? [], 'تصویر کارت ملی'), (string)$order['tracking_code'], 'national-card');
    $now = db_now(); $deleteAfter = gmdate('Y-m-d H:i:sP', time() + 30 * 86400);
    $pdo->beginTransaction();
    $pdo->prepare('INSERT INTO verifications (id,order_id,provider,receipt_location,receipt_mime,bank_card_location,bank_card_mime,national_card_location,national_card_mime,national_id_ciphertext,card_last4,consent_at,status,delete_after,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
        ->execute([db_id('ver_'),$order['id'],$receipt['provider'],$receipt['provider']==='local'?$receipt['pathname']:$receipt['url'],$receipt['mime'],$bankCard['provider']==='local'?$bankCard['pathname']:$bankCard['url'],$bankCard['mime'],$nationalCard['provider']==='local'?$nationalCard['pathname']:$nationalCard['url'],$nationalCard['mime'],encrypt_sensitive($nationalId),$cardLast4,$now,'pending',$deleteAfter,$now,$now]);
    $pdo->prepare('UPDATE orders SET status=?, payment_status=?, identity_status=?, updated_at=? WHERE id=?')->execute(['verification_review','receipt_submitted','pending',$now,$order['id']]);
    $pdo->prepare('INSERT INTO order_events (id,order_id,event_type,previous_status,new_status,actor,metadata,created_at) VALUES (?,?,?,?,?,?,?,?)')
        ->execute([db_id('evt_'),$order['id'],'verification_submitted',$order['status'],'verification_review','customer',db_json(['card_last4'=>$cardLast4]),$now]);
    $pdo->commit();
    json_response(['ok'=>true,'message'=>'مدارک با موفقیت و به‌صورت امن ارسال شد.','order_id'=>$order['tracking_code']]);
} catch (InvalidArgumentException $error) {
    json_response(['ok'=>false,'message'=>$error->getMessage()], 422);
} catch (Throwable $error) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log('REDT verification error: ' . $error->getMessage());
    json_response(['ok'=>false,'message'=>'ذخیره امن مدارک انجام نشد؛ دوباره تلاش کنید.'], 500);
}
