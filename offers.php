<?php

require_once "DBAccess.php"; 

$fileHTML = file_get_contents("offers.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();

$offerte_super = $connessione->getData("offers where class='super' and final_date>CURDATE()");
$offerte_special =  $connessione->getData("offers where class='special' and final_date>CURDATE()");
$offerte_gruppi =  $connessione->getData("offers where class='groups' and final_date>CURDATE()");
$offerte_carnet =  $connessione->getData("offers where class='carnet' and final_date>CURDATE()");

$connessione->closeConnection();

$offerte_superList = '';
$offerte_specialList = '';
$offerte_gruppiList = '';
$offerte_carnetList = '';

if ($offerte_super != null) {
    foreach ($offerte_super as $offerta) {
        $offerte_superList .= '<a class="offer offer--'. $offerta['nome'] . '" data-code="'. $offerta['discount_code'] .'" href="#">
                            <div class="offer__body">
                                <h3 class="offer__title">'.$offerta['title'].'</h3>
                                <p class="offer__content">'.$offerta['content'].'</p>
                            </div>
                        </a>';
    }   
}
if ($offerte_special != null) {
    foreach ($offerte_special as $offerta) {
        $offerte_specialList .= '<a class="offer offer--'. $offerta['nome'] . '" data-code="'. $offerta['discount_code'] .'" href="#">
                            <div class="offer__body">
                                <h3 class="offer__title">'.$offerta['title'].'</h3>
                                <p class="offer__content">'.$offerta['content'].'</p>
                            </div>
                        </a>';
    }  
}
if ($offerte_gruppi != null) {
    foreach ($offerte_gruppi as $offerta) {
        $offerte_gruppiList .= '<a class="offer offer--'. $offerta['nome'] . '" data-code="'. $offerta['discount_code'] .'" href="#">
                            <div class="offer__body">
                                <h3 class="offer__title">'.$offerta['title'].'</h3>
                                <p class="offer__content">'.$offerta['content'].'</p>
                            </div>
                        </a>';
    }  
}
if ($offerte_carnet != null) {
    foreach ($offerte_carnet as $offerta) {
        $offerte_carnetList .= '<a class="offer offer--'. $offerta['nome'] . '" data-code="'. $offerta['discount_code'] .'" href="#">
                            <div class="offer__body">
                                <h3 class="offer__title">'.$offerta['title'].'</h3>
                                <p class="offer__content">'.$offerta['content'].'</p>
                            </div>
                        </a>';
    }  
}
$fileHTML = str_replace("<offerte_super/>", $offerte_superList, $fileHTML);
$fileHTML = str_replace("<offerte_speciali/>", $offerte_specialList, $fileHTML);
$fileHTML = str_replace("<offerte_gruppi/>", $offerte_gruppiList, $fileHTML);
$fileHTML = str_replace("<offerte_carnet/>", $offerte_carnetList, $fileHTML);

echo $fileHTML;
?>