<?php
session_start();

require_once "funzioni.php"; 
require_once "DBAccess.php";

$fileHTML = file_get_contents("buy.html");

use DB\DBAccess;
$connessione = new DBAccess();
$connessione->openDBConnection();

$warnings = "";
$name = "";
$surname = "";
$email = "";
$birthday = "";
$card = "";
$cvv = "";
$expiration_date = "";


if (isset($_POST['submit'])) {
  (!empty($_POST['name']) && preg_match("/^[a-zA-Z\s]+$/", $_POST['name'])) ?
    $name = $_POST['name'] :
    $warnings .= '<p class="form__error" id="name_error">Inserisci un nome corretto</p>';
  (!empty($_POST['surname']) && preg_match("/^[a-zA-Z\s]+$/", $_POST['surname'])) ?
    $surname = $_POST['surname'] :
    $warnings .= '<p class="form__error" id="surname_error">Inserisci un cognome corretto</p>';
  (!empty($_POST['email']) && preg_match("/^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $_POST['email'])) ?
    $email = $_POST['email'] :
    $warnings .= '<p class="form__error" id="email_error">Inserisci una email corretta</p>';
  (!empty($_POST['birthday']) && $_POST['birthday'] < new Datetime()) ?
    $birthday = $_POST['birthday'] :
    $warnings .= '<p class="form__error" id="birthday_error">Inserisci una data di nascita corretta</p>';
  (!empty($_POST['numero_carta']) && preg_match("/^[0-9]{16}$/", str_replace(" ", "", clearInput($_POST['numero_carta'])))) ?
    $card = $_POST['numero_carta'] :
    $warnings .= '<p class="form__error" id="card_error">Inserisci un numero di carta corretto</p>';
  (!empty($_POST['cvv']) && preg_match("/^[0-9]{3}$/", $_POST['cvv'])) ?
    $cvv = $_POST['cvv'] :
    $warnings .= '<p class="form__error" id="cvv_error">Inserisci un cvv corretto</p>';
  (!empty($_POST['scadenza_carta']) && preg_match("/^(0[1-9]|1[0-2])\/\d{2}$/", $_POST['scadenza_carta'])) ?
    $expiration_date = $_POST['scadenza_carta'] :
    $warnings .= '<p class="form__error" id="card_date_error">Inserisci una scadenza corretta</p>';

  if ($warnings == "") {
    for($i = 0; $i < $_SESSION['ricerca']['seats']; $i++) {
      $connessione->addData("insert into ticket (".(isset($_SESSION['user']) ? "user_id, " : "")." route_schedule_id, departure_station_id, arrival_station_id, departure_time, category)
                            values (".(isset($_SESSION['user']) ? $_SESSION['user'].", " : "").$_SESSION['ricerca']['schedule'].", '".$_SESSION['ricerca']['from']."', '"
                            .$_SESSION['ricerca']['to']."', '".(new DateTime($_SESSION['ricerca']['date']))->format('Y/m/d')." ".$_SESSION['ricerca']['departure_time']."', '"
                            .$_SESSION['ricerca']['class']."')");
    }
    $_SESSION['message'] = isset($_SESSION['user']) ? 
    "<p class=\"message js-success-message\">Grazie per aver acquistato il biglietto su Iberu Trasporti. Puoi trovare il tuo biglietto nella sezione 'Generale' del tuo account.</p>" : 
    "<p class=\"message js-success-message\">Grazie per aver acquistato il biglietto su Iberu Trasporti. Il biglietto è stato spedito alla tua mail personale.</p>";;
    unset($_SESSION['ricerca']);
    header('Location: index.php');
    exit();
  }
}
$fileHTML = str_replace("<warnings/>", $warnings, $fileHTML);


$departure_date = new DateTime($_SESSION['ricerca']['date']);

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a href="./login.php" class="nav__link">Area Riservata</a>', $fileHTML);
if (isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="adminpage.php" lang="en-US">Account</a>', $fileHTML);
if (isset($_SESSION['user'])) {
  $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="userpage.php" lang="en-US">Account</a>', $fileHTML);
  $user_info = $connessione->getDataArray("select * from user where id = '".$_SESSION['user']."'")[0];
  $name = $user_info['first_name'];
  $surname = $user_info['last_name'];
  $email = $user_info['email'];
  $birthday = $user_info['birthday'];
}

$qResult_route = $connessione->getDataArray("select route_id from route_schedule where id = '".$_SESSION['ricerca']['schedule']."'");
$qResult_duration = $connessione->getDataArray("select timediff(end.duration, start.duration) as time_difference
              from route_station as start join route_station as end on start.route_id = end.route_id and start.route_id="
              .$qResult_route[0]." where start.station_id = '".$_SESSION['ricerca']['from']."' and end.station_id = 
              '".$_SESSION['ricerca']['to']."'");
$qResult_train = $connessione->getDataArray("select train_id from route_schedule where id = '".$_SESSION['ricerca']['schedule']."'");
$qResult_train = $connessione->getDataArray("select train.name from train where train.id=$qResult_train[0]");

$fileHTML = str_replace("<warnings/>", $warnings, $fileHTML);
$fileHTML = str_replace("<departure_station/>", $_SESSION['ricerca']['from'], $fileHTML);
$fileHTML = str_replace("<arrival_station/>", $_SESSION['ricerca']['to'], $fileHTML);
$fileHTML = str_replace("<duration/>", $qResult_duration[0], $fileHTML);
$fileHTML = str_replace("<departure_station_time/>", $_SESSION['ricerca']['departure_time'], $fileHTML);
$fileHTML = str_replace("<arrival_station_time/>", $_SESSION['ricerca']['arrival_time'], $fileHTML);
$fileHTML = str_replace("<departure_datetime/>", $departure_date->format("d/m/Y")." ".$_SESSION['ricerca']['departure_time'], $fileHTML);
$fileHTML = str_replace("<departure_d/>", $departure_date->format("Y-m-d")." ".$_SESSION['ricerca']['departure_time'], $fileHTML);
$fileHTML = str_replace("<total_seats/>", $_SESSION['ricerca']['seats'], $fileHTML);
$fileHTML = str_replace("<train/>", $qResult_train[0], $fileHTML);
$fileHTML = str_replace("<class/>", ($_SESSION['ricerca']['class'] == 1 ? "Prima classe" : "Seconda classe"), $fileHTML);
$fileHTML = str_replace("<price/>", $_SESSION['ricerca']['price'], $fileHTML);
$fileHTML = str_replace("<seats/>", $_SESSION['ricerca']['seats'], $fileHTML);

$fileHTML = str_replace("<first_name/>", $name, $fileHTML);
$fileHTML = str_replace("<last_name/>", $surname, $fileHTML);
$fileHTML = str_replace("<email/>", $email, $fileHTML);
$fileHTML = str_replace("<birthday/>", $birthday, $fileHTML);
$fileHTML = str_replace("<card/>", $card, $fileHTML);
$fileHTML = str_replace("<cvv/>", $cvv, $fileHTML);
$fileHTML = str_replace("<expiration_date/>", $expiration_date, $fileHTML);
echo $fileHTML;
?>