<?php
session_start();

require_once "funzioni.php";
require_once "DBAccess.php";

$fileHTML = file_get_contents("login.html");

use DB\DBAccess;

$connection = new DBAccess();
$connection->openDBConnection();

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<span class="nav__link">Area Riservata</span>', $fileHTML);
if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}

$warnings = "";
$email = "";
$password = "";

if (isset($_POST['submit'])) {
  $email = clearInput($_POST['email']);
  $password = clearInput($_POST['new_password']);

  if (empty($email) || empty($password) || !preg_match("/^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
      $warnings = '<p class="form__error" id="login_error">Inserisci email e password</p>';
  }
  else {
    if ($connection->checkLogin($email, $password)) {
      $_SESSION['user'] = $connection->getDataArray("select id from user where email = '$email'")[0];
      $connection->closeConnection();
      header("Location: userpage.php");
      exit();
    } 
    else if ($connection->checkLoginAdmin($email, $password)) {
      $_SESSION['admin'] = $connection->getDataArray("select id from admin where email = '$email'")[0];
      $connection->closeConnection();
      header("Location: adminpage.php");
      exit();
    }
    else $warnings = '<p class="form__error" id="login_error">Email o password errati</p>';
  }
}

$fileHTML = str_replace("<warnings/>", $warnings, $fileHTML);
$fileHTML = str_replace("<email/>", $email, $fileHTML);
echo $fileHTML;
?>