<?php
include('../../_includes/setup.php');

try {
  $data = $db->prepare("UPDATE qrcodes SET title = :title, link = :link WHERE id = :id LIMIT 1;");
  $data->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
  $data->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
  $data->bindValue(':link', $_POST['forward'], PDO::PARAM_STR);
  $data->execute();
} catch (PDOException $e) {
 echo $e->getMessage();
}

?>
