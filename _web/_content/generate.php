<?php
include('../../_includes/setup.php');

$url = $_POST['scheme'].$_POST['domain']."/".$_POST['uri'];
$qrCode = new Endroid\QrCode\QrCode($url);
$qrCode->setSize(500);
$qrCode->setMargin(10);
$qrCode->setEncoding('UTF-8');
$qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::MEDIUM());
$qrCode->setValidateResult(true);
$userid = $_SESSION['loggedin']-$_ENV['HIDE'];

try {
  $data = $db->prepare("INSERT INTO qrcodes (user_id,uri,link,active,`usage`,created) VALUES (:userid,:uri,:link,1,0,NOW());");
  $data->bindValue(':userid', $userid, PDO::PARAM_INT);
  $data->bindValue(':uri', $_POST['uri'], PDO::PARAM_STR);
  $data->bindValue(':link', $_POST['forward'], PDO::PARAM_STR);
  $data->execute();
  $id = $db->lastInsertId();
} catch (PDOException $e) {
 echo $e->getMessage();
}

$qrCode->writeFile(__DIR__.'/../qr/'.$id.'.png');
$qrCode->writeFile(__DIR__.'/../qr/'.$id.'.svg');
$qrCode->writeFile(__DIR__.'/../qr/'.$id.'.eps');

?>
<div style="padding: 10px; background-color: #eee; margin-top: 20px; border-radius: 3px; ">
  <img src="/qr/<?php echo $id; ?>.png" style="width: 150px; height: auto; margin-bottom: 10px;" /><br />
  <a href="/qr/<?php echo $id; ?>.eps" class="btn btn-outline-primary" download>Download .EPS</a>
  <a href="/qr/<?php echo $id; ?>.png" class="btn btn-outline-primary" download>Download .PNG</a>
  <a href="/qr/<?php echo $id; ?>.svg" class="btn btn-outline-primary" download>Download .SVG</a>
</div>
