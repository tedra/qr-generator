<?php
include('../../_includes/setup.php');

$url = $_POST['scheme'].$_POST['domain']."/".$_POST['uri'];
$qrCode = new Endroid\QrCode\QrCode($url);
$qrCode->setSize(500);
$qrCode->setMargin(10);
$qrCode->setEncoding('UTF-8');
$qrCode->setErrorCorrectionLevel(Endroid\QrCode\ErrorCorrectionLevel::MEDIUM());
$qrCode->setValidateResult(true);
$userid = $_SESSION['loggedin']-$_ENV['HIDE'];

try {
  $data = $db->prepare("INSERT INTO qrcodes (user_id,title, uri,link,active,`usage`,created) VALUES (:userid,:title,:uri,:link,1,0,NOW());");
  $data->bindValue(':userid', $userid, PDO::PARAM_INT);
  $data->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
  $data->bindValue(':uri', $_POST['uri'], PDO::PARAM_STR);
  $data->bindValue(':link', $_POST['forward'], PDO::PARAM_STR);
  $data->execute();
  $id = $db->lastInsertId();
} catch (PDOException $e) {
 echo $e->getMessage();
}

$qrCode->writeFile(__DIR__.'/../qr/'.$_POST['uri'].'.png');
$qrCode->writeFile(__DIR__.'/../qr/'.$_POST['uri'].'.svg');
$qrCode->writeFile(__DIR__.'/../qr/'.$_POST['uri'].'.eps');

?>
