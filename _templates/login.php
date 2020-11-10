<div class="container">
<?php if (isset($error) && $error > 0) { ?>
  <div class="alert alert-danger" role="alert"><?php echo $message; ?></div>
<?php } ?>

<div class="row" style="margin-top: 20px;">
  <div class="col-6">
<form method="post" action="">
  <input type="hidden" name="login" value="1" />
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
  </div>
  <button type="submit" class="btn btn-success">Login</button>
</form>
</div>
</div>
</div>
