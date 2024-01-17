<?php

require_once "DBAccess.php"; 

$fileHTML = file_get_contents("index.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();

$comunicazioni= $connessione->getData("news where initial_date>= CURDATE() order by initial_date");
$newsList = '';
$mesi = array('01' => 'Gennaio', '02' => 'Febbraio', '03' => 'Marzo',
    '04' => 'Aprile', '05' => 'Maggio', '06' => 'Giugno',
    '07' => 'Luglio', '08' => 'Agosto', '09' => 'Settembre',
    '10' => 'Ottobre', '11' => 'Novembre', '12' => 'Dicembre'
);

if($comunicazioni != null){
    foreach($comunicazioni as $comunicazione){
        
        $timestamp = strtotime($comunicazione['initial_date']);
        $giorno = date('d', $timestamp);
        $mese = date('m', $timestamp);
        $anno = date('Y', $timestamp);

        $nomeMese = $mesi[$mese];

        //$contenuto=str_replace('#', '<time>', $comunicazione['contenuto'], 1);

        $newsList .='<article class="news js-news">
        .<time class="news__date" datetime="'.$comunicazione['initial_date'].'">
          <span class="news__day">'.$giorno.'</span>
          <span class="news__month">'.$nomeMese.' '.$anno.'</span></time>
        <div class="news__body">
          <h3 class="news__title">'.$comunicazione['title'].'</h3>
          <p class="news__content js-news__content">'.$comunicazione['content'].'</p>
        </div>
        <button aria-label="Espandi la notizia" class="ri-arrow-down-s-line news__expand js-news__expand"></button>
      </article>';
    }
}
else{
    $newsList = "<p> Nessuna comunicazione presente oggi </p>";
}
$fileHTML = str_replace("<comunicazioni/>", $newsList, $fileHTML);
//$comunicazioni->free();
//$newsList->free();

$offerte = $connessione->getData("offers");
$connessione->closeConnection();

$offerteList = '';
if ($offerte != null) {
    foreach ($offerte as $offerta) {
        $offerteList .= '<a id="offer__' . $offerta['name'] . '" class="offer" href="#">
                            <div class="offer__body">
                                <h3 class="offer__title">'.$offerta['body'].'</h3>
                                <p class="offer__content">'.$offerta['more'].'</p>
                            </div>
                        </a>';
    }   
}
$fileHTML = str_replace("<offerte/>", $offerteList, $fileHTML);
//$offerte->free();
//$offerteList->free();

echo $fileHTML;
?>
