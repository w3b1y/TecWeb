<?php
session_start();

require_once "funzioni.php"; 
require_once "DBAccess.php";

$fileHTML = file_get_contents("buy.html");

use DB\DBAccess;
$connessione = new DBAccess();
$connessione->openDBConnection();

$warnings = "";

$departure_date = new DateTime($_SESSION['ricerca']['date']);

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a href="./login.php" class="nav__link">Area Riservata</a>', $fileHTML);
if (isset($_SESSION['user'])) $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="userpage.php" lang="en-US">Account</a>', $fileHTML);
if (isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="adminpage.php" lang="en-US">Account</a>', $fileHTML);

$qResult_duration = $connessione->getDataArray("select timediff(end.duration, start.duration) as time_difference
              from route_station as start join route_station as end on start.route_id = end.route_id
              where start.station_id = '".$_SESSION['ricerca']['from']."' and end.station_id = '".$_SESSION['ricerca']['to']."'");
$qResult_train = $connessione->getDataArray("select train_id from route_schedule where id = '".$_SESSION['ricerca']['route_schedule']."'");

$fileHTML = str_replace("<warnings/>", $warnings, $fileHTML);
$fileHTML = str_replace("<departure_station/>", $_SESSION['ricerca']['from'], $fileHTML);
$fileHTML = str_replace("<arrival_station/>", $_SESSION['ricerca']['to'], $fileHTML);
$fileHTML = str_replace("<duration/>", $qResult_duration[0], $fileHTML);
$fileHTML = str_replace("<departure_station_time/>", $_SESSION['ricerca']['departure_time'], $fileHTML);
$fileHTML = str_replace("<arrival_station_time/>", $_SESSION['ricerca']['arrival_time'], $fileHTML);
$fileHTML = str_replace("<departure_datetime/>", $departure_date->format("d/m/Y")." ".$_SESSION['ricerca']['departure_time'], $fileHTML);
$fileHTML = str_replace("<total_seats/>", $_SESSION['ricerca']['seats'], $fileHTML);
$fileHTML = str_replace("<train/>", $qResult_train[0], $fileHTML);
$fileHTML = str_replace("<train/>", ($_SESSION['ricerca']['class'] == 1 ? "Prima classe" : "Seconda classa"), $fileHTML);
$fileHTML = str_replace("<price/>", $_SESSION['ricerca']['price'], $fileHTML);
echo $fileHTML;
?>