<?php
session_start();

require_once "funzioni.php";
require_once "DBAccess.php";

$fileHTML = file_get_contents("userpage.html");

use DB\DBAccess;
$connessione = new DBAccess();
$connessione->openDBConnection();

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit();
}
if (isset($_SESSION['user'])) $fileHTML = str_replace("<navbar/>", '<span class="nav__link" lang="en-US">Account</span>', $fileHTML);


$user = $_SESSION['user'];


if (isset($_POST['submit'])) {
  $name = clearInput($_POST['name']);
  $surname = clearInput($_POST['surname']);
  $email = clearInput($_POST['email']);
  $birthday = $_POST['birthday'];
  $old_password = clearInput($_POST['old_password']);
  $new_password = clearInput($_POST['new_password']);
  $rnew_password = clearInput($_POST['rnew_password']);
  $error = null;

  $user_info = $connessione->getDataArray("select * from user where id = '$user'")[0];
  (!empty($name) && preg_match("/^[a-zA-Z\s]+$/", $name) && $name != $user_info['first_name']) ?
    $connessione->addData("update user set first_name = '$name' where id = '$user'") :
    $error .= '<p class="form__error" id="name_error">Inserisci un nome corretto</p>';
  (!empty($surname) && preg_match("/^[a-zA-Z\s]+$/", $surname) && $surname != $user_info['last_name']) ?
    $connessione->addData("update user set last_name = '$surname' where id = '$user'") : 
    $error .= '<p class="form__error" id="name_error">Inserisci un nome corretto</p>';
  (!empty($email) && preg_match("/^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email) &&
            $email != $user_info['email']) ?
    $connessione->addData("update user set email = '$email' where id = '$user'") :
    $error .= '<p class="form__error" id="email_error">Inserisci una email corretta</p>';
  (!empty($birthday) && $birthday != $user_info['birthday'] && $birthday < new Datetime()) ?
    $connessione->addData("update user set birthday = '$birthday' where id = '$user'") :
    $error .= '<p class="form__error" id="birthday_error">Inserisci una data di nascita corretta</p>';
  (!empty($old_password) && !empty($new_password) && !empty($rnew_password) &&
      $new_password == $rnew_password && $old_password == $user_info['password'] && $old_password != $new_password) ? 
    $connessione->addData("update user set password = '$new_password' where id = '$user'") :
    $error .= '<p class="form__error" id="password_error">Errore nel cambio password</p>';
}



$ticket = "";
$valid_ticket = $connessione->getDataArray("select * from ticket where user_id = '$user' and departure_time >= curdate() order by departure_time asc");
if (empty($valid_ticket)) {
  $ticket = '<p class="container__message">Attualmente non hai biglietti validi disponibili</p>';
}
else {
  foreach ($valid_ticket as $vt) {
    $departure_station = $vt['departure_station_id'];
    $arrival_station = $vt['arrival_station_id'];
    $qResult_duration = $connessione->getDataArray("select timediff(end.duration, start.duration) as time_difference
              from route_station as start join route_station as end on start.route_id = end.route_id
              where start.station_id = '$departure_station' and end.station_id = '$arrival_station'");
    $departure_time_station = (new DateTime($vt['departure_time']));
    $arrival_time_station = clone $departure_time_station;
    $arrival_time_station->add(getDateInterval($qResult_duration[0]));
  
    $train_id = $connessione->getDataArray("select route_schedule.train_id from route_schedule where route_schedule.id=$vt[route_schedule_id]");
    $ticket .= '<article class="ticket">
                <dl class="ticket__route--horizontal">
                  <dt class="route__term--horizontal">'.$departure_station.'</dt>
                  <dd class="route__data--horizontal"><time datetime="'.$departure_time_station->format('H:i').'">'
                  .$departure_time_station->format('H:i').'</time></dd>
                  <dt class="route__term--horizontal route__term--line">Durata</dt>
                  <dd class="route__data--horizontal route__data--line">'.$qResult_duration[0].'</dd>
                  <dt class="route__term--horizontal">'.$arrival_station.'</dt>
                  <dd class="route__data--horizontal"><time datetime="'.$arrival_time_station->format('H:i').'">'
                  .$arrival_time_station->format('H:i').'</time></dd>
                </dl>
                <div class="container ticket__description">
                  <p class="ticket__content">Data: <time datetime="'.$departure_time_station->format('d/m/y H:i').'">'
                  .$departure_time_station->format('d/m/Y H:i').'</time></p>
                  <p class="ticket__content">Identificativo treno: '.$train_id[0].'</p>
                  <p class="ticket__class js-first__class ticket__class--selected">'.($vt['category'] == 1 ? "Prima classe" : "Seconda classe" ).'</p>
                </div>
                </article>';
  }
}
$fileHTML = str_replace("<tickets/>", $ticket, $fileHTML);


$trow = "";
$message = "";
$invalid_ticket = $connessione->getDataArray("select * from ticket where user_id = '$user' order by departure_time asc");
if (empty($invalid_ticket)) {
  $message = '<p class="container__message">Scegli Iberu per il tuo prossimo viaggio e immergiti in un&#39;avventura unica: il biglietto per un&#39;esperienza senza eguali ed è solo un click di distanza!</p>';
  $fileHTML = str_replace("<usertable/>", $table, $fileHTML);
}
else {
  foreach ($invalid_ticket as $it) {
    $qResult_route = $connessione->getDataArray("select route_id from route_schedule where id = '".$it['route_schedule_id']."'");
    $qResult_price = $connessione->getDataArray("select start.price - end.price from route_station as start join route_station as end on 
              start.route_id = end.route_id where start.station_id = '".$it['departure_station_id']."' 
              and end.station_id = '".$it['arrival_station_id']."' and start.route_id = '".$qResult_route[0]."'");
    $trow .= '<tr class="table__row">
                <td class="table__data" data-cell="Data"><time datetime="'.$it['departure_time'].'">'.str_replace('-', '/', $it['departure_time']).'</time></td>
                <td class="table__data" data-cell="Tratta">'.$it['departure_station_id'].' - '.$it['arrival_station_id'].'</td>
                <td class="table__data" data-cell="Prezzo">€'.$qResult_price[0].'</td>
              </tr>';
  }
}
$fileHTML = str_replace("<message/>", $message, $fileHTML);
$fileHTML = str_replace("<trow/>", $trow, $fileHTML);


$user_info = $connessione->getDataArray("select * from user where id = '$user'")[0];
$fileHTML = str_replace("<first_name/>", $user_info['first_name'], $fileHTML);
$fileHTML = str_replace("<last_name/>", $user_info['last_name'], $fileHTML);
$fileHTML = str_replace("<email/>", $user_info['email'], $fileHTML);
$fileHTML = str_replace("<birthday/>", $user_info['birthday'], $fileHTML);
echo $fileHTML;
?>