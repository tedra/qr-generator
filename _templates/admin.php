<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">filter.ar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" id="newcode" href="#">New QR code</a>
      </li>
    </ul>
      <a href="/?logout=1" class="btn btn-sm btn-outline-danger my-2 my-sm-0">logout</a>
  </div>
</nav>

<div class="container">
<?php if (isset($error) && $error > 0) { ?>
  <div class="alert alert-danger" role="alert"><?php echo $message; ?></div>
<?php } ?>

<div id="newcode-div" style="display: none; border: 1px solid #ddd; border-radius: 3px; margin-top: 20px; padding: 20px;">
<h6>New QR Code</h6>

<form method="post" action="" id="form">
  <input type="hidden" name="login" value="1" />

  <div class="form-group">
  <label for="exampleInputEmail1">Title:</label>
  <input type="text" id="title" name="title" required class="form-control form-control-sm">
</div>

<label for="exampleInputEmail1">Short URL:</label>
  <div class="input-group">
    <select name="domain" id="domain" class="form-control form-control-sm">
      <option value="filter.ar">filter.ar</option>
    </select>
<span class="input-group-addon">&nbsp;/&nbsp;</span>

    <input type="text" id="uri" name="uri" maxlength=11 minlength=3 required class="form-control form-control-sm" style="border-radius-top-left: 3px;">
  </div>
    <div class="form-group">

  </div>

  <div class="form-group">
  <label for="exampleInputEmail1">Redirecting To:</label>
  <input type="text" id="link" name="link" required class="form-control form-control-sm">
</div>

  <button type="submit" id="generate" class="btn btn-sm btn-outline-success">Save</button>&nbsp;
  <a href="#" id="close" class="btn btn-sm btn-outline-danger">Cancel</a>
</form>
</div>

<div class="links" style="margin-top:20px;">

</div>
</div>

<div class="modal fade" id="QRModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">QR Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" id="qrmodalimg" style="width: 100%;"/>
      </div>
    </div>
  </div>
</div>

<script>

$.ajax({
    type: "POST",
    url: '/_content/links.php',
    data: { ajax: 1, delete: <?php echo $delete; ?> },
    success: function(data) {
      $('.links').html(data);
    }
  });

  $('body').on('click','#generate',function(e) {
    e.stopPropagation();
    e.preventDefault();
    var domain = $('#domain').val();
    var scheme = "https://";
    var uri = $('#uri').val();
    var title = $('#title').val();
    var forward = $('#link').val();
    $.ajax({
        type: "POST",
        url: '/_content/generate.php',
        data: { title: title, forward: forward, scheme: scheme, domain: domain, uri: uri, ajax: 1 },
        success: function(data) {
          $('#newcode-div').fadeOut('fast');
          $.ajax({
              type: "POST",
              url: '/_content/links.php',
              data: { ajax: 1, new: 1 },
              success: function(data) {
                $('.links').fadeOut('fast',function(e) { $(this).html(data).fadeIn('fast'); });
              }
            });
        }
    });
  });

  $('body').on('click','#close',function(e) {
    e.stopPropagation();
    e.preventDefault();
    $('#newcode-div').fadeOut('fast');
  });

  $('body').on('click','#newcode',function(e) {
    e.stopPropagation();
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: '/_content/uri.php',
        data: { ajax: 1 },
        success: function(data) {
          $('#uri').val(data);
        }
      });
    $('#newcode-div').fadeIn('fast');
  });
</script>
