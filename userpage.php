<?php
session_start();

$fileHTML = file_get_contents("userpage.html");

use DB\DBAccess;
$connessione = new DBAccess();
$connessione->openDBConnection();

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

?>