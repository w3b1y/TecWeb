<?php
session_start();

require_once "funzioni.php";
require_once "DBAccess.php";

use DB\DBAccess;

$connection = new DBAccess();
$connection->openDBConnection();

if (isset($_SESSION['user'])) unset($_SESSION['user']);
if (isset($_SESSION['admin'])) unset($_SESSION['admin']);
header("Location: index.php");
  exit();
?>