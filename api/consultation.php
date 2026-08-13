<?php
declare(strict_types=1);
require __DIR__.'/../config/config.php';
if(!is_post()) json_response(['ok'=>false,'message'=>'درخواست نامعتبر است.'],405);
if(!csrf_valid($_POST['csrf']??null)) json_response(['ok'=>false,'message'=>'نشست منقضی شده؛ صفحه را تازه کنید.'],419);
if(!empty($_POST['website'])) json_response(['ok'=>true,'message'=>'درخواست شما ثبت شد.']);
$last=(int)($_SESSION['last_submit']??0); if(time()-$last<20) json_response(['ok'=>false,'message'=>'لطفاً کمی بعد دوباره تلاش کنید.'],429);
$name=trim((string)($_POST['name']??'')); $phone=preg_replace('/[^0-9+]/','',(string)($_POST['phone']??'')); $service=trim((string)($_POST['service']??'')); $message=trim((string)($_POST['message']??'')); $errors=[];
if(mb_strlen($name)<3)$errors['name']='نام کامل را وارد کنید.'; if(!preg_match('/^(?:\+98|0)?9\d{9}$/',$phone))$errors['phone']='شماره موبایل معتبر نیست.'; if($service==='')$errors['service']='یک خدمت انتخاب کنید.'; if(mb_strlen($message)<10)$errors['message']='کمی بیشتر درباره پروژه بنویسید.';
if($errors)json_response(['ok'=>false,'message'=>'لطفاً خطاهای فرم را اصلاح کنید.','errors'=>$errors],422);
if(!is_dir(STORAGE_PATH.'/messages'))mkdir(STORAGE_PATH.'/messages',0755,true); $record=json_encode(['date'=>date(DATE_ATOM),'name'=>$name,'phone'=>$phone,'service'=>$service,'message'=>$message],JSON_UNESCAPED_UNICODE).PHP_EOL; file_put_contents(STORAGE_PATH.'/messages/consultations.log',$record,FILE_APPEND|LOCK_EX); $_SESSION['last_submit']=time(); json_response(['ok'=>true,'message'=>'درخواست شما ثبت شد؛ به‌زودی تماس می‌گیریم.']);
