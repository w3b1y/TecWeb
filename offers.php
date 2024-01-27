<?php
session_start();

require_once "DBAccess.php"; 

$fileHTML = file_get_contents("offers.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();

$offerte_super = $connessione->getData("offers where class='super' and final_date>CURDATE()");
$offerte_special =  $connessione->getData("offers where class='special' and final_date>CURDATE()");
$offerte_studenti = $connessione->getData("offers where class='student' and final_date>CURDATE()");
$offerte_gruppi =  $connessione->getData("offers where class='groups' and final_date>CURDATE()");


$connessione->closeConnection();

$offerte_superList = '';
$offerte_specialList = '';
$offerte_studentList = '';
$offerte_gruppiList = '';

if ($offerte_super != null) {
    foreach ($offerte_super as $offerta) {
        $offerte_superList .= '<a class="offer offer--'. $offerta['nome'] . '" href="./index.php?discount_code=VWX9012">
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
if ($offerte_studenti != null) {
    foreach ($offerte_studenti as $offerta) {
        $offerte_studentList .= '<a class="offer offer--'. $offerta['nome'] . '" href="#">
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
$fileHTML = str_replace("<offerte_super/>", $offerte_superList, $fileHTML);
$fileHTML = str_replace("<offerte_speciali/>", $offerte_specialList, $fileHTML);
$fileHTML = str_replace("<offerte_studenti/>", $offerte_studentList, $fileHTML);
$fileHTML = str_replace("<offerte_gruppi/>", $offerte_gruppiList, $fileHTML);

echo $fileHTML;
?>