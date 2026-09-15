<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';
if(!is_post())json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'],405);
if(!csrf_valid($_POST['csrf']??null))json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'],419);
$normalizeDigits=static fn(string $value):string=>strtr($value,['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']);
$code=strtoupper(trim((string)($_POST['tracking_code']??'')));
$phone=preg_replace('/[^0-9+]/','',$normalizeDigits((string)($_POST['phone']??'')));
if(!preg_match('/^RDT-[A-F0-9]{10}$/',$code)||!preg_match('/^(?:\+98|0)?9\d{9}$/',(string)$phone))json_response(['ok'=>false,'message'=>'کد پیگیری و شماره موبایل را بررسی کنید.'],422);
$status=null;
try{
    $statement=db()->prepare('SELECT o.status FROM orders o JOIN customers c ON c.id=o.customer_id WHERE o.tracking_code=? AND c.phone=? LIMIT 1');
    $statement->execute([$code,$phone]);
    $value=$statement->fetchColumn();
    if(is_string($value))$status=$value;
}catch(Throwable){
    foreach(admin_order_rows() as $row){if(($row['order_id']??'')===$code&&($row['phone']??'')===$phone){$status=(string)($row['_status']??'new');break;}}
}
if($status===null)json_response(['ok'=>false,'message'=>'سفارشی با این مشخصات پیدا نشد.'],404);
$labels=['new'=>'در انتظار بررسی','awaiting_payment'=>'در انتظار هماهنگی پرداخت','processing'=>'در حال انجام','ready'=>'آماده تحویل','completed'=>'تحویل شده','cancelled'=>'لغو شده'];
json_response(['ok'=>true,'status'=>$status,'status_label'=>$labels[$status]??'در حال بررسی']);
