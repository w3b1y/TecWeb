<?php
session_start();

require_once "DBAccess.php"; 
require_once "funzioni.php"; 

$fileHTML = file_get_contents("adminpage.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();
$avvisi='';

//PAGINA COMUNICAZIONI

//variabili per il form
$data_i = '';
$data_f = '';
$titolo = '';
$contenuto = '';

//al click di insert_submit
if(isset($_POST['insert_news'])){
    $data_i = $_POST['date_start'];
    $data_f = $_POST['date_end'];
    $titolo = clearInput($_POST['news_title']);
    $contenuto = clearInput($_POST['news_content']);

    if(!empty($data_i) && !empty($data_f) && !empty($titolo) && !empty($contenuto) && $data_f>=$data_i){
        $connessione->addComunication($data_i,$data_f,$titolo,$contenuto);
        //feed-back
    }
    else{
        if(empty($data_i)){
            $avvisi .='<p class="form__error" id="initial_date_empty">Inserisci una data iniziale</p>';
        } 
        if(empty($data_f)){
            $avvisi .='<p class="form__error" id="final_date_empty">Inserisci una data finale</p>';
        } 
        if($data_f>=$data_i){
            $avvisi .= '<p class="form__error" id="datetime_error">La data finale deve essere maggiore o uguale alla data iniziale</p>';
        }
        if(empty($titolo)){
            $avvisi .='<p class="form__error" id="initial_date_empty">Inserisci un titolo</p>';
        }      
        $fileHTML = str_replace("&lt;data_i/>", $data_i, $fileHTML);
        $fileHTML = str_replace("&lt;data_f/>", $data_f, $fileHTML);
        $fileHTML = str_replace("&lt;titolo/>", $titolo, $fileHTML);
        $fileHTML = str_replace("<contenuto/>", $contenuto, $fileHTML);

    }
}
$fileHTML = str_replace("<avvisi/>", $avvisi, $fileHTML);

if(isset($_GET['delete_news'])){
    $elimina=$_GET['selected_news'];
    $connessione->deleteComunication($elimina);
}

$comunicazioni= $connessione->viewComunication();
$newsList = '';
if($comunicazioni != null){
    foreach($comunicazioni as $comunicazione){       
        $newsList .='<option value="'.$comunicazione['id'].'">'.$comunicazione['title'].'</option>';
    }
}
else{
    $newsList = "<p>Nessuna comunicazione presente</p>";
}
$fileHTML = str_replace("<news/>", $newsList, $fileHTML);


//PAGINA OFFERTE

//variabili per il form
$classe = '';
$nome = '';
$titolo = '';
$contenuto = '';
$codice_sconto = '';
$percentuale = '';
$data_fine = '';
$minimo = '';
$img = '';

//al click di insert_submit
if(isset($_POST['insert_offer'])){
    $classe = $_POST['offer_class'];
    $nome = clearInput($_POST['offer_name']);
    $titolo = clearInput($_POST['offer_title']);
    $contenuto = $_POST['offer_content'];
    $codice_sconto = clearInput($_POST['discount_code']);
    $percentuale = $_POST['discount_percentage'];
    $data_fine = $_POST['offer_end'];
    $minimo = $_POST['minimun'];
    $img = clearInput($_POST['image']);

    if(!empty($classe) && !empty($nome) && !empty($titolo) && !empty($contenuto) && !empty($codice_sconto) && !empty($percentuale) && !empty($data_fine) && !empty($img)){
        if(($classe!="group") || ($classe=="group" && !empty($minimo) && $minimo>=3)){
            $connessione->addOffer($classe, $nome, $titolo, $contenuto, $codice_sconto, $percentuale, $data_fine, $minimo, $img);
        }
    }
    else{
         echo "errore";
    }
}
$fileHTML = str_replace("<avvisi/>", $avvisi, $fileHTML);

if(isset($_GET['delete_offer'])){
    $elimina=$_GET['selected_offer'];
    $connessione->deleteOffer($elimina);
}

$offerte= $connessione->viewOffers();
$offerteList = '';
if($offerte != null){
    foreach($offerte as $offerta){       
        $offerteList .='<option value="'.$offerta['id'].'">'. $offerta['class'].' - '.$offerta['nome'].' - '.$offerta['title'].'</option>';
    }
}
else{
    $offerteList = "<p>Nessuna offerta presente</p>";
}
$fileHTML = str_replace("<offerte/>", $offerteList, $fileHTML);

echo $fileHTML;
?>