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
  if (!empty($surname) && preg_match("/^[a-zA-Z\s]+$/", $surname) && $surname != $user_info['last_name'])
    $connessione->addData("update user set last_name = '$surname' where id = '$user'");
  if (!empty($email) && preg_match("/^(?!.*\.\.)[a-zA-Z0-9]+([._]*[a-zA-Z0-9])*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email) &&
            $email != $user_info['email'])
    $connessione->addData("update user set email = '$email' where id = '$user'");
  if (!empty($birthday) && $birthday != $user_info['birthday'])
    $connessione->addData("update user set birthday = '$birthday' where id = '$user'");
  if (!empty($old_password) && !empty($new_password) && !empty($rnew_password) &&
      $new_password == $rnew_password && $old_password == $user_info['password'] && $old_password != $new_password)
    $connessione->addData("update user set password = '$new_password' where id = '$user'");
  
  if ($error == null) {
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
  } 
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



$subscription = "";
$valid_subscription = $connessione->getDataArray("select * from user_subscription where user_id = '$user' and end_date >= curdate() order by end_date asc");
if (empty($valid_subscription)) {
  $subscription = '<p class="container__message">Attualmente non hai abbonamenti attivi disponibili</p>';
}
else {
  foreach ($valid_subscription as $vs) {
    $sub = $connessione->getDataArray("select * from subscription where id = $vs[subscription_id]");
    $subscription .= '<article class="subscription">
            <header id="'.$sub[0]['icon_id'].'" class="subscription__header container--dynamic">
              <h4 class="subscription__title dynamic__heading-margin">Abbonamento '.$sub[0]['category'].'</h4>
              <a href="./subscriptions.html#'.$sub[0]['link'].'" class="subscription__description subscription__description--type dynamic__content-margin">'.$sub[0]['name'].'</a>
              <p class="subscription__description subscription__description--dates dynamic__content-margin">
                <span>Data inizio:<time datetime="'.$vs['start_date'].'">'.$vs['start_date'].'</time></span>
                <span>Data termine:<time datetime="'.$vs['end_date'].'">'.$vs['end_date'].'</time></span>
              </p>
            </header>
          </article>';
  }
}
$fileHTML = str_replace("<subscription/>", $subscription, $fileHTML);



$table = "";
$invalid_ticket = $connessione->getDataArray("select * from ticket where user_id = '$user' order by departure_time asc");
$invalid_subscription = $connessione->getDataArray("select * from user_subscription where user_id = '$user' order by end_date asc");
if (empty($invalid_ticket) && empty($invalid_subscription)) {
  $table = '<p class="container__message">Scegli Iberu per il tuo prossimo viaggio e immergiti in un&#39;avventura unica: il biglietto per un&#39;esperienza senza eguali ed è solo un click di distanza!</p>';
  $fileHTML = str_replace("<usertable/>", $table, $fileHTML);
}
if (!empty($invalid_ticket)) {
  $trow = "";
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
  $table .= '<table class="table">
              <caption class="table__caption">Tabella biglietti acquistati</caption>
              <thead class="table__header">
                <tr class="table__row">
                  <th scope="col" class="table__head">Data</th>
                  <th scope="col" class="table__head">Tratta</th>
                  <th scope="col" class="table__head">Prezzo</th>
                </tr>
              </thead>
              <tbody class="table__body">
                '.$trow.'
              </tbody>
              </table>';
}
if (!empty($invalid_subscription)) {
  $trow = "";
  foreach ($invalid_subscription as $is) {
    $sub = $connessione->getDataArray("select * from subscription where id = $is[subscription_id]");
    $trow .= '<tr class="table__row">
                  <td class="table__data" data-cell="Data"><time datetime="'.$is['start_date'].'">'.str_replace('-', '/', $is['start_date']).'</time> - 
                  <time datetime="'.$is['end_date'].'">'.str_replace('-', '/', $is['end_date']).'</time></td>
                  <td class="table__data" data-cell="Categoria">'.$sub[0]['category'].'</td>
                  <td class="table__data" data-cell="Tratta">'.$sub[0]['name'].'</td>
                  <td class="table__data" data-cell="Prezzo">€'.$sub[0]['price'].'</td>
                </tr>';
  }
  $table .= '<table class="table">
              <caption class="table__caption">Tabella abbonamenti acquistati</caption>
              <thead class="table__header">
                <tr class="table__row">
                  <th scope="col" class="table__head">Data</th>
                  <th scope="col" class="table__head">Categoria</th>
                  <th scope="col" class="table__head">Tratta</th>
                  <th scope="col" class="table__head">Prezzo</th>
                </tr>
              </thead>
              <tbody class="table__body">
                '.$trow.'
              </tbody>
              </table>';
}
$fileHTML = str_replace("<usertable/>", $table, $fileHTML);


$user_info = $connessione->getDataArray("select * from user where id = '$user'")[0];
$form = '<form action="userpage.php" method="post">
              <fieldset class="container__fieldset--user">
                <legend>Informazioni personali</legend>
                <label class="container__label" for="name">Nome</label>
                <input class="container__input--search" type="text" name="name" id="name" value="'.$user_info['first_name'].'">
                <label class="container__label" for="surname">Cognome</label>
                <input class="container__input--search" type="text" name="surname" id="surname" value="'.$user_info['last_name'].'">
                <label class="container__label" for="email">Email</label>
                <input class="container__input--search" type="email" name="email" id="email" value="'.$user_info['email'].'">
                <label class="container__label" for="birthday">Data di nascita</label>
                <input class="container__input--search" type="date" name="birthday" id="birthday" value="'.$user_info['birthday'].'">
              </fieldset>
              <fieldset class="container__fieldset--user">
                <legend>Cambio Password</legend>
                <label class="container__label" for="old_passwor">Vecchia password</label>
                <input class="container__input--search" type="password" name="old_password" id="old_password">
                <label class="container__label" for="new_password">Nuova password</label>
                <input class="container__input--search" type="password" name="new_password" id="new_password">
                <label class="container__label" for="rnew_password">Ripeti password</label>
                <input class="container__input--search" type="password" name="rnew_password" id="rnew_password">
              </fieldset>
              <input class="submit" type="submit" value="Conferma" name="submit">
              </form>';
$fileHTML = str_replace("<userform/>", $form, $fileHTML);
echo $fileHTML;
?>