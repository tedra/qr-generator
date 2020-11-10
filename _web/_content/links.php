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

<?php if (isset($_POST['new']) && $_POST['new'] == 1) { ?>
  <div class="alert alert-success" role="alert">New QR code created. See details below.</div>
<?php } ?>

<?php if (isset($_POST['delete']) && $_POST['delete'] == 1) { ?>
  <div class="alert alert-warning" role="alert">QR code deleted.</div>
<?php } ?>

<table class="table">
<thead>
  <tr>
    <th scope="col"></th>
    <th scope="col">Short URL</th>
    <th scope="col">Forwarding URL</th>
    <th scope="col">Scans</th>
    <th scope="col"></th>
  </tr>
</thead>
<tbody>
<?php for ($x=0;$x<sizeof($links);$x++) { ?>
<tr<?php if (isset($_POST['new']) && $_POST['new'] == 1 && $x == 0) { echo " class='table-warning'"; }?>>
  <td>
    <a class="btn btn-sm btn-outline-secondary" href="#" data-toggle="modal" data-target="#QRModal" data-uri="<?php echo $links[$x]['uri'];?>">QR code</a>
    <a href="https://filter.ar/<?php echo $links[$x]['uri'];?>" data-url="https://filter.ar/<?php echo $links[$x]['uri'];?>" class="copyurl btn btn-sm btn-outline-secondary"><i class="far fa-copy"></i></a></td>
  <td><a href="https://filter.ar/<?php echo $links[$x]['uri'];?>" target="_new"><?php echo $links[$x]['title'];?></a><br />
  <td><a href="<?php echo $links[$x]['link']?>" target="_new" class="btn btn-sm btn-outline-secondary"><i class="fas fa-external-link-alt"></i></a></td>
  <td><?php echo $links[$x]['usage']?></td>
  <td class="text-right">
    <div class="btn-group">
      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Download
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="#">Edit</a>
        <div class="dropdown-divider"></div>

        <div class="dropdown-divider"></div>
        <a class="dropdown-item" download href="/qr/<?php echo $links[$x]['uri']?>.png">.PNG</a>
        <a class="dropdown-item" download href="/qr/<?php echo $links[$x]['uri']?>.svg">.SVG</a>
        <a class="dropdown-item" download href="/qr/<?php echo $links[$x]['uri']?>.eps">.EPS</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">View Statistics</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/admin/?delete=<?php echo $links[$x]['id']; ?>">Delete</a>

      </div>
    </div></td>
</tr>
<?php } ?>
</tbody>
</table>

<script>
$('#QRModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var uri = button.data('uri')
  var modal = $(this)
  $('#qrmodalimg').attr('src',"/qr/"+uri+".png");
})

function fallbackCopyTextToClipboard(text) {
var textArea = document.createElement("textarea");
textArea.value = text;
textArea.style.position="fixed";  //avoid scrolling to bottom
document.body.appendChild(textArea);
textArea.focus();
textArea.select();

try {
  var successful = document.execCommand('copy');
  var msg = successful ? 'successful' : 'unsuccessful';
  console.log('Fallback: Copying text command was ' + msg);
} catch (err) {
  console.error('Fallback: Oops, unable to copy', err);
}

document.body.removeChild(textArea);
}
function copyTextToClipboard(text) {
if (!navigator.clipboard) {
  fallbackCopyTextToClipboard(text);
  return;
}
navigator.clipboard.writeText(text).then(function() {
  console.log('Async: Copying to clipboard was successful!');
}, function(err) {
  console.error('Async: Could not copy text: ', err);
});
}

$('body').on('click','.copyurl',function(e) {
  e.preventDefault();
  copyTextToClipboard($(this).data('url'));
  $(this).css('color','red');
});


</script>
