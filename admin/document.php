<?php
declare(strict_types=1);
require __DIR__ . '/../config/config.php';
if (!admin_is_authenticated()) { http_response_code(401); exit('Unauthorized'); }
$id = preg_replace('/[^a-zA-Z0-9_]/', '', (string) ($_GET['id'] ?? ''));
$type = (string) ($_GET['type'] ?? '');
$columns = ['receipt'=>['receipt_location','receipt_mime'],'bank_card'=>['bank_card_location','bank_card_mime'],'national_card'=>['national_card_location','national_card_mime']];
if ($id === '' || !isset($columns[$type])) { http_response_code(400); exit('Bad request'); }
try {
    [$locationColumn,$mimeColumn]=$columns[$type];
    $statement=db()->prepare("SELECT provider,$locationColumn AS location,$mimeColumn AS mime FROM verifications WHERE id=?");
    $statement->execute([$id]); $row=$statement->fetch();
    if(!is_array($row)) throw new RuntimeException('Not found');
    $file=private_blob_read((string)$row['provider'],(string)$row['location']);
    header('Content-Type: '.($file['mime'] ?: $row['mime']));
    header('Cache-Control: private, no-store, max-age=0');
    header('Content-Disposition: inline; filename="document.'.($file['mime']==='image/png'?'png':($file['mime']==='image/webp'?'webp':'jpg')).'"');
    echo $file['body'];
} catch(Throwable){ http_response_code(404); echo 'Document unavailable'; }
