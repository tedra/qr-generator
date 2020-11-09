<?php
  include('../../_includes/setup.php');
  $userid = $_SESSION['loggedin']-$_ENV['HIDE'];
  try {
    $data = $db->prepare("SELECT * FROM qrcodes WHERE user_id = :userid AND active = 1 ORDER BY created DESC;");
    $data->bindValue(':userid', $userid, PDO::PARAM_INT);
    $data->execute();
    $links = $data->fetchAll();
  } catch (PDOException $e) {
   echo $e->getMessage();
  }
?>
<table class="table table-striped table-bordered">
<thead>
  <tr>
    <td>Link</td>
    <td>Scans</td>
    <td></td>
  </tr>
</thead>
<tbody>
<?php for ($x=0;$x<sizeof($links);$x++) { ?>
<tr>
  <td><a href="https://filter.ar/<?php echo $links[$x]['uri'];?>">https://filter.ar/<?php echo $links[$x]['uri'];?></a><br />
  <small><i>Links to: <?php echo $links[$x]['link'];?></i></small><br />
  <a href="/qr/<?php echo $links[$x]['id']?>.png" download class="btn btn-sm btn-outline-secondary">Download .PNG</a>
<a href="/qr/<?php echo $links[$x]['id']?>.eps" download class="btn btn-sm btn-outline-secondary">Download .EPS</a>
<a href="/qr/<?php echo $links[$x]['id']?>.svg" download class="btn btn-sm btn-outline-secondary">Download .SVG</a></td>
  <td><?php echo $links[$x]['usage']?></td>
  <td><a href="#" class="btn btn-sm btn-outline-secondary">Stats</a>&nbsp;<a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>&nbsp;<a href="#" class="btn btn-sm btn-outline-danger">Delete</a></td>
</tr>
<?php } ?>
</tbody>
</table>
