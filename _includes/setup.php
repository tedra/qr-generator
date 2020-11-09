<?php

ob_start();
header('Content-Type: text/html; charset=utf-8');

if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
  require '../../vendor/autoload.php';
} else {
  require '../vendor/autoload.php';
}

use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Response\QrCodeResponse;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();

if (isset($_ENV['DEBUG']) && $_ENV['DEBUG'] == 1) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
} else {
  error_reporting(0);
  ini_set('display_errors', 0);
}

set_time_limit(0);
ini_set('memory_limit', -1);

ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();

if (isset($_GET['clear']) && $_GET['clear'] == 1) {
  session_destroy();
}

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
  session_destroy();
}

date_default_timezone_set('Europe/London');
setlocale(LC_ALL, 'en_US.utf-8');
$today = date('Y-m-d');
$modified = date('Y-m-d H:i:s');
$now = time();

$db = new PDO('mysql:host='.$_ENV['DB_SERV'].';dbname='.$_ENV['DB_BASE'].';sslmode=require;port='.$_ENV['DB_PORT'].';charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASS']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

include('functions.php');

$line = explode('?',$_SERVER['REQUEST_URI']);
$uri = get_uri($line[0]);

if (isset($_GET['genpass']) && $_GET['genpass'] <> '') {
  echo sha1($_GET['genpass'].$_ENV['SALT']);
}

$templates = array('admin');
$error = 0;

if (isset($uri[1]) && in_array($uri[1],$templates) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] > 0 && isset($_SESSION['hash']) && sha1(($_SESSION['loggedin']-$_ENV['HIDE']).$_ENV['SALT']) == $_SESSION['hash']) {
  $template = $uri[1];
} else if (isset($uri[1]) && $uri[1] <> '') {
  try {
    $data = $db->prepare("SELECT * FROM qrcodes WHERE uri = :link AND active = 1 LIMIT 1;");
    $data->bindValue(':link', $uri[1], PDO::PARAM_STR);
    $data->execute();
    $link = $data->fetch();
  } catch (PDOException $e) {
   echo $e->getMessage();
  }
  if (isset($link) && $link['id'] > 0) {
    try {
      $data = $db->prepare("UPDATE qrcodes SET `usage` = `usage` + 1 WHERE uri = :link AND active = 1 LIMIT 1;");
      $data->bindValue(':link', $uri[1], PDO::PARAM_STR);
      $data->execute();
    } catch (PDOException $e) {
     echo $e->getMessage();
    }
    header('location:'.$link['link']);
    exit();
  } else {
    if (empty($_POST['ajax']) || $_POST['ajax'] < 1) {
      unset($_SESSION['loggedin']);
      unset($_SESSION['hash']);
  }
}
} else {
  if (empty($_POST['ajax']) || $_POST['ajax'] < 1) {
    unset($_SESSION['loggedin']);
    unset($_SESSION['hash']);
  }
}

if (isset($_POST['login']) && $_POST['login'] == 1 && isset($_POST['email']) && $_POST['email'] <> '' && isset($_POST['password']) && $_POST['password'] <> '') {
  try {
    $data = $db->prepare("SELECT * FROM users WHERE email = :email AND hash = :hash AND active = 1 LIMIT 1;");
    $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $data->bindValue(':hash', sha1($_POST['password'].$_ENV['SALT']), PDO::PARAM_STR);
    $data->execute();
    $user = $data->fetch();
  } catch (PDOException $e) {
   echo $e->getMessage();
  }

  if (isset($user['id']) && $user['id'] > 0) {
    $error = 0;
    $_SESSION['loggedin'] = $user['id']+$_ENV['HIDE'];
    $_SESSION['hash'] = sha1($user['id'].$_ENV['SALT']);
    header('location:/admin/');
    exit();
  } else {
    $error++;
    $message = "Incorrect Email or Password. Please retry.";
  }

}
?>
