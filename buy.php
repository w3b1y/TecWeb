<?php
session_start();

require_once "funzioni.php"; 
require_once "DBAccess.php";

$fileHTML = file_get_contents("buy.html");

isset($_SESSION['user']) ? 
    $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="userpage.php" lang="en-US">Account</a>', $fileHTML) : 
    $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="login.php">Area Riservata</a>', $fileHTML);

echo $fileHTML;
?>