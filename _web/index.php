<?php
include('../_includes/setup.php');
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/bootstrap.min.css" crossorigin="anonymous">
    <title>filter.ar</title>
    <script src="https://code.jquery.com/jquery.min.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/88ea9bd1aa.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="/js/bootstrap.min.js" crossorigin="anonymous"></script>
  </head>
  <body>
<?php if (isset($template) && $template <> '') {
  include('../_templates/'.$template.'.php');
} else {
  include('../_templates/login.php');
} ?>
  </body>
</html>
