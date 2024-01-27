<?php
session_start();

require_once "DBAccess.php"; 
require_once "funzioni.php"; 

$fileHTML = file_get_contents("index.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();
$avvisi="";
$departure_station = '<label class="visually-hidden" for="from">Partenza</label>
                        <input class="container__input--search" type="text" name="from" id="from" placeholder="Partenza">';
$arrival_station = '<label class="visually-hidden" for="to">Arrivo</label>
                    <input class="container__input--search" type="text" name="to" id="to" placeholder="Arrivo">';
$discount_code = '<label class="visually-hidden" for="discount">Codice Sconto</label>
                    <input class="container__input--search" type="text" name="discount_code" id="discount" placeholder="Codice sconto">';
$date = '<label class="visually-hidden" for="date">Data</label>
            <input class="container__input--search" type="datetime-local" name="date" id="date">';
$passengers = '<label class="visually-hidden" for="seats">Passeggeri (massimo 35 passeggeri)</label>
                <input class="container__input--search" type="number" name="seats" id="seats" placeholder="1" min="1" max="35" value="1">';

if(isset($_POST['submit'])){
    $stazionePartenza = clearInput($_POST['from']);
    $stazioneArrivo = clearInput($_POST['to']);
    $dataOra = clearInput($_POST['date']);
    $dataOggi = date('Y-m-d H:i');
    $nPasseggeri = intval($_POST['seats']);
    $discount_code = isset($_POST['discount_code']) ? clearInput($_POST['discount_code']) : null;

    if(!empty($stazionePartenza) && !empty($stazioneArrivo) && $dataOra>$dataOggi && $nPasseggeri>=1){
        $partenza = $connessione->checkStazione($stazionePartenza);
        $arrivo = $connessione->checkStazione($stazioneArrivo);

        if($partenza!=null && $arrivo!=null){
            $_SESSION['ricerca'] = array('from' => $stazionePartenza, 'to' => $stazioneArrivo, 'date' => $dataOra, 'seats' => $nPasseggeri);
            $connessione->closeConnection();
            header("Location: tickets.php");
            exit;
        }
        else{
            if($partenza==null){
                $avvisi .='<p class="form__error" id="departure_station_error">Inserisci la stazione di partenza corretta</p>';
            }
            if($arrivo==null){
                $avvisi .='<p class="form__error" id="arrival_station_error">Inserisci la stazione di arrivo corretta</p>';
            }
                        
        }
    }
    empty($stazionePartenza) ? 
        $avvisi .='<p class="form__error" id="departure_station_empty">Inserisci la stazione di partenza</p>' : 
        $departure_station = '<label class="visually-hidden" for="from">Partenza</label>
                                <input class="container__input--search" type="text" name="from" id="from" placeholder="Partenza" value="'.$stazionePartenza.'">';
    empty($stazioneArrivo) ? 
        $avvisi .='<p class="form__error" id="arrival_station_empty">Inserisci la stazione di arrivo</p>' : 
        $arrival_station = '<label class="visually-hidden" for="to">Arrivo</label>
                            <input class="container__input--search" type="text" name="to" id="to" placeholder="Arrivo" value="'.$stazioneArrivo.'">';
    if(!empty($discount_code)) 
        $discount_code = '<label class="visually-hidden" for="discount">Codice Sconto</label>
                            <input class="container__input--search" type="text" name="discount_code" id="discount" placeholder="Codice sconto" 
                            value='.$discount_code.'">';
    ($dataOra < $dataOggi) ? 
        $avvisi .= '<p class="form__error" id="datetime_error">La data e l&#39;ora devono essere maggiori o uguali a quelli attuali</p>' : 
        $date = '<label class="visually-hidden" for="date">Data e ora</label>
                  <input class="container__input--search" type="datetime-local" name="date" id="date" value="'.$dataOra.'">';
    ($nPasseggeri < 1 || $nPasseggeri > 35) ? 
        $avvisi .= '<p class="form__error" id="passengers_error">Inserire un numero di passeggeri compreso tra 1 e 35</p>' : 
        $passengers = '<label class="visually-hidden" for="seats">Numero passeggeri</label>
                  <input class="container__input--search" type="number" name="seats" id="seats" value="'.$nPasseggeri.'">';
}

$form = '<form class="container__form--search js-container__form--search" action="" method="post">
        <avvisi/>
        <fieldset class="posrel">
        <legend class="visually-hidden"><span lang="en-US">Fieldset</span> per inserimento stazioni</legend>
        '.$departure_station.$arrival_station.'
        <button id="swap"><span class="visually-hidden">Inverti</span> <span class="ri-arrow-up-down-line"></span></button>
        </fieldset>
        <fieldset>
        <legend class="visually-hidden"><span lang="en-US">Fieldset</span> per inserimento filtri di ricerca</legend>
        '.$discount_code.$date.$passengers.'
        <label class="visually-hidden" for="submit">Cerca</label>
        <input class="submit" type="submit" id="submit" value="Cerca" name="submit">
        </fieldset>
        </form>';

$fileHTML = str_replace("<search/>", $form, $fileHTML);
$fileHTML = str_replace("<avvisi/>", $avvisi, $fileHTML);

$comunicazioni= $connessione->getData("news where final_date>= CURDATE() order by final_date limit 3");
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

$offerte = $connessione->getData("offers where final_date>CURDATE() and class='super' or class='special' order by final_date limit 2");
$connessione->closeConnection();

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
