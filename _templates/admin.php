<a href="#" id="newcode" class="btn btn-success">Generate New QR Code</a>&nbsp;<a href='/?logout=1' class="btn btn-danger">Log-out</a><br />
<div id="newcode-div" style="display: none; border: 1px solid #ddd; border-radius: 3px; margin-top: 20px; padding: 20px;">
<h4>Generate New QR Code</h4>

<form method="post" action="" id="form">
  <input type="hidden" name="login" value="1" />

  <div class="form-group">
    <label for="exampleInputEmail1">Domain</label>
    <select name="domain" id="domain" class="form-control">
      <option value="filter.ar">filter.ar</option>
    </select>
  </div>
    <div class="form-group">
    <label for="exampleInputEmail1">URI</label>
    <input type="text" id="uri" name="uri" maxlength=11 minlength=3 required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>

  <div class="form-group">
  <label for="exampleInputEmail1">Forwarding Link</label>
  <input type="text" id="link" name="link" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
</div>

  <button type="submit" id="generate" class="btn btn-success">Generate</button>&nbsp;<a href="#" id="close" class="btn btn-warning">Close</a>
</form>
<div class="results"></div>
</div>

<div class="links" style="margin-top:20px;">

</div>

<script>

$.ajax({
    type: "POST",
    url: '/_content/links.php',
    data: { ajax: 1 },
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
    var forward = $('#link').val();
    $.ajax({
        type: "POST",
        url: '/_content/generate.php',
        data: { forward: forward, scheme: scheme, domain: domain, uri: uri, ajax: 1 },
        success: function(data) {
          $('.results').html(data);
          $('#generate').addClass('disabled');
          $('#generate').prop('disabled',true);
          $('#form').prop('disabled',true);
          $.ajax({
              type: "POST",
              url: '/_content/links.php',
              data: { ajax: 1 },
              success: function(data) {
                $('.links').html(data);
              }
            });
        }
    });
  });

  $('body').on('click','#close',function(e) {
    e.stopPropagation();
    e.preventDefault();
    $('#newcode-div').hide();
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
    $('.results').html('');
    $('#generate').removeClass('disabled');
    $('#generate').prop('disabled',false);
    $('#form').prop('disabled',false);
    $('#newcode-div').show();
  });
</script>
