<?php
session_start();

require_once "DBAccess.php"; 
require_once "funzioni.php"; 

$fileHTML = file_get_contents("index.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();
$avvisi="";

if(isset($_POST['submit'])){
    $stazionePartenza = clearInput($_POST['from']);
    $stazioneArrivo = clearInput($_POST['to']);

    if(!empty($stazionePartenza) && !empty($stazioneArrivo)){
        $partenza = $connessione->checkStazione($stazionePartenza);
        $arrivo = $connessione->checkStazione($stazioneArrivo);

        if($partenza!=null && $arrivo!=null){
            $_SESSION['ricerca'] = array('from' => $stazionePartenza, 'to' => $stazioneArrivo);
            $connessione->closeConnection();
            header("Location: tickets.php");
            exit;
        }
        else{
            if($partenza==null){
                $avvisi .="<li>Immettere una stazione di partenza corretta</li>";
            }
            if($arrivo==null){
                $avvisi .="<li>Immettere una stazione di arrivo corretta</li>";
            }
                        
        }
    }
    if(empty($stazionePartenza)){
        $avvisi .="<li>Immettere una stazione di partenza</li>";
    }
    if(empty($stazioneArrivo)){
        $avvisi .="<li>Immettere una stazione di arrivo</li>";
    }
}

if($avvisi != ""){
    $avvisi = "<div class ='error'><ul class='flex_column no_gap'>" . $avvisi . "</ul> </div>";//DA SISTEMARE
}
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
