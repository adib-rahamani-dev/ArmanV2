<?php
declare(strict_types=1);
require __DIR__.'/../config/config.php';
if(!is_post()) json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'],405);
if(!csrf_valid($_POST['csrf']??null)) json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'],419);
if(!empty($_POST['website'])) json_response(['ok'=>true,'message'=>'درخواست ثبت شد.']);
$last=(int)($_SESSION['last_order']??0); if(time()-$last<20) json_response(['ok'=>false,'message'=>'لطفاً کمی بعد دوباره تلاش کنید.'],429);
$service=trim((string)($_POST['service']??'')); $budget=trim((string)($_POST['budget']??'')); $message=trim((string)($_POST['message']??'')); $name=trim((string)($_POST['name']??'')); $phone=preg_replace('/[^0-9+]/','',(string)($_POST['phone']??''));
if($service===''||$budget===''||mb_strlen($message)<10||mb_strlen($name)<3||!preg_match('/^(?:\+98|0)?9\d{9}$/',$phone)) json_response(['ok'=>false,'message'=>'لطفاً همه اطلاعات را درست کامل کنید.'],422);
if(!is_dir(STORAGE_PATH.'/messages')) mkdir(STORAGE_PATH.'/messages',0755,true); $record=json_encode(['date'=>date(DATE_ATOM),'service'=>$service,'budget'=>$budget,'message'=>$message,'name'=>$name,'phone'=>$phone],JSON_UNESCAPED_UNICODE).PHP_EOL; file_put_contents(STORAGE_PATH.'/messages/orders.log',$record,FILE_APPEND|LOCK_EX); $_SESSION['last_order']=time(); json_response(['ok'=>true,'message'=>'درخواست شما ثبت شد.']);
