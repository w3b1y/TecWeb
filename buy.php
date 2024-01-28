<?php
session_start();

require_once "funzioni.php"; 
require_once "DBAccess.php";

$fileHTML = file_get_contents("buy.html");
echo $fileHTML;
?>