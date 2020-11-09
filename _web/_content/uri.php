<?php
  include('../../_includes/setup.php');
  echo $hash = PseudoCrypt::hash(rand(1,99999), 5);
?>
