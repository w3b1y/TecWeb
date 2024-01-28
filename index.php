<?php
session_start();

require_once "DBAccess.php"; 
require_once "funzioni.php"; 

$fileHTML = file_get_contents("index.html");

use DB\DBAccess;

$connection = new DBAccess();
$connection->openDBConnection();

isset($_SESSION['user']) ? 
    $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="userpage.php" lang="en-US">Account</a>', $fileHTML) : 
    $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="login.php">Area Riservata</a>', $fileHTML);

$warnings="";
$departure_station = "";
$arrival_station = "";
$discount_code = "";
$datetime = "";
$passengers = "";


if (isset($_POST['submit'])) handleFormSubmission();

function handleFormSubmission() {
    global $warnings, $departure_station, $arrival_station, $datetime, $passengers, $discount_code, $connection;
    $from = clearInput($_POST['from']);
    $to = clearInput($_POST['to']);
    $date = clearInput($_POST['date']);
    $today = date('Y-m-d H:i');
    $seats = intval($_POST['seats']);
    $discount = isset($_POST['discount_code']) ? clearInput($_POST['discount_code']) : null;
    if (validateForm($from, $to, $date, $today, $seats)) {
        $qResult_from = $connection->checkStazione($from);
        $qResult_to = $connection->checkStazione($to);
        if ($qResult_from != null && $qResult_to != null) {
            $_SESSION['ricerca'] = array('from' => $from, 'to' => $to, 'date' => $date, 'seats' => $seats, 'discount_code' => $discount);
            $connection->closeConnection();
            header("Location: tickets.php");
            exit;
        } else handleStationErrors($qResult_from, $qResult_to);
    }
}

function validateForm($from, $to, $date, $today, $seats) {
    global $warnings, $departure_station, $arrival_station, $datetime, $passengers, $discount_code, $connection;
    empty($from) ? 
    $warnings .= '<p class="form__error" id="departure_station_empty">Inserisci la stazione di partenza</p>' :
    $departure_station = $from;
    empty($to) ?
    $warnings .= '<p class="form__error" id="arrival_station_empty">Inserisci la stazione di arrivo</p>' :
    $arrival_station = $to;
    $date < $today ?
    $warnings .= '<p class="form__error" id="datetime_error">La data e l&#39;ora devono essere maggiori o uguali a quelli attuali</p>' :
    $datetime = $date;
    ($seats < 1 || $seats > 35) ?
    $warnings .= '<p class="form__error" id="passengers_error">Inserire un numero di passeggeri compreso tra 1 e 35</p>' :
    $passengers = $seats;
    if (!empty($discount))
    $discount_code = $discount;
    return empty($warnings);
}

function handleStationErrors($from, $to) {
    global $warnings;
    if ($from == null) $warnings .= '<p class="form__error" id="departure_station_error">Inserisci la stazione di partenza corretta</p>';
    if ($to == null) $warnings .= '<p class="form__error" id="arrival_station_error">Inserisci la stazione di arrivo corretta</p>';
}

$fileHTML = str_replace("<warnings/>", $warnings, $fileHTML);
$fileHTML = str_replace("<departure_station/>", $departure_station, $fileHTML);
$fileHTML = str_replace("<arrival_station/>", $arrival_station, $fileHTML);
$fileHTML = str_replace("<departure_time/>", $datetime, $fileHTML);
$fileHTML = str_replace("<total_seats/>", $passengers, $fileHTML);
$fileHTML = str_replace("<discount_code/>", $discount_code, $fileHTML);


$comunicazioni= $connection->getData("news where final_date>= CURDATE() order by final_date limit 3");
$newsList = '';
$mesi = array('01' => 'Gennaio', '02' => 'Febbraio', '03' => 'Marzo',
    '04' => 'Aprile', '05' => 'Maggio', '06' => 'Giugno',
    '07' => 'Luglio', '08' => 'Agosto', '09' => 'Settembre',
    '10' => 'Ottobre', '11' => 'Novembre', '12' => 'Dicembre'
);

if($comunicazioni != null){
    foreach($comunicazioni as $comunicazione){
                
        $init_date = strtotime($comunicazione['initial_date']);
        $end_date = strtotime($comunicazione['final_date']);
        $giorno = date('d', $init_date);
        $mese = date('m', $init_date);
        $anno = date('Y', $init_date);
        $nomeMese = $mesi[$mese];

        $f_init_date = date('d/m/Y', $init_date);
        $f_final_date= date('d/m/Y', $end_date);

        $content = str_replace('#i', '<time datetime='.$init_date.'>'.$f_init_date.'</time>', $comunicazione['content']);
        $content = str_replace('#f', '<time datetime='.$end_date.'>'.$f_final_date.'</time>', $content);

        $newsList .='<article class="news js-news">
        .<time class="news__date" datetime="'.$comunicazione['initial_date'].'">
          <span class="news__day">'.$giorno.'</span>
          <span class="news__month">'.$nomeMese.' '.$anno.'</span></time>
        <div class="news__body">
          <h3 class="news__title">'.$comunicazione['title'].'</h3>
          <p class="news__content js-news__content">'.$content.'</p>
        </div>
        <button aria-label="Espandi la notizia" class="ri-arrow-down-s-line news__expand js-news__expand"></button>
      </article>';
    }
}

else{
    $newsList = "<p> Nessuna comunicazione presente oggi </p>";
}
$fileHTML = str_replace("<comunicazioni/>", $newsList, $fileHTML);

$offerte = $connection->getData("offers where final_date>CURDATE() and class='super' or class='special' order by final_date limit 2");
$connection->closeConnection();

$offerteList = '';
if ($offerte != null) {
    foreach ($offerte as $offerta) {
        $offerteList .= '<a id="offer offer--' . $offerta['nome'] . '" class="offer" href="#">
                            <div class="offer__body">
                                <h3 class="offer__title">'.$offerta['title'].'</h3>
                                <p class="offer__content">'.$offerta['content'].'</p>
                            </div>
                        </a>';
    }   
}
$fileHTML = str_replace("<offerte/>", $offerteList, $fileHTML);

echo $fileHTML;
?>
