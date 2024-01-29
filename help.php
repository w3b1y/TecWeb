<?php
session_start();

require_once "funzioni.php"; 
require_once "DBAccess.php";

$fileHTML = file_get_contents("help.html");

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a href="./login.php" class="nav__link">Area Riservata</a>', $fileHTML);
if (isset($_SESSION['user'])) $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="userpage.php" lang="en-US">Account</a>', $fileHTML);
if (isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="adminpage.php" lang="en-US">Account</a>', $fileHTML);

echo $fileHTML;
?>