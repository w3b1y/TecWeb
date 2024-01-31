<?php
session_start();

require_once "DBAccess.php"; 
require_once "funzioni.php"; 

$fileHTML = file_get_contents("adminpage.html");
header('Cache-Control: max-age=31536000');

use DB\DBAccess;

$connection = new DBAccess();
$connection->openDBConnection();

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit();
}
else if (!isset($_SESSION['admin'])) {
  header('Location: index.php');
  exit();
}
if (isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a href="./logout.php" class="nav__link" lang="en-US">Logout</a>', $fileHTML);


$warnings = '';
$start_date = '';
$end_date = '';
$title = '';
$content = '';
$message = '';

if(isset($_POST['insert_news'])){
  if(!empty($_POST['news__date--start']) && !empty($_POST['news__date--start']) && !empty($_POST['news__date--end']) &&
    !empty(clearInput($_POST['news__title'])) && !empty(clearInput($_POST['news__content'])) && $_POST['news__date--end'] >= $_POST['news__date--start']) {
    $connection->addComunication($_POST['news__date--start'], $_POST['news__date--end'], $_POST['news__title'], $_POST['news__content']);
    $message='<p aria-role="alert" class="message" id="insert_news">Inserimento notizia avvenuto con successo</p>';
    $fileHTML = str_replace("<operazione_avvenuta_news/>", $message, $fileHTML);
  }
  else{
    (empty($_POST['news__date--start'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="initial_date_empty">Inserisci una data iniziale</p>' :
      $start_date = $_POST['news__date--start'];
    (empty($_POST['news__date--end'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="final_date_empty">Inserisci una data finale</p>' :
      $end_date = $_POST['news__date--end'];
    (empty($_POST['news__title'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="title_empty">Inserisci un titolo</p>' :
      $title = $_POST['news__title'];
    (empty($_POST['news__content'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="content_empty">Inserisci un titolo</p>' :
      $content = $_POST['news__content'];
    if($_POST['news__date--end']<$_POST['news__date--start'])
      $warnings .= '<p aria-role="alert" class="form__error" id="datetime_error">La data finale deve essere maggiore o uguale alla data iniziale</p>';  
  }
}
$fileHTML = str_replace("<operazione_avvenuta_news/>", $message, $fileHTML);
$fileHTML = str_replace("&lt;data_i/>", $start_date, $fileHTML);
$fileHTML = str_replace("&lt;data_f/>", $end_date, $fileHTML);
$fileHTML = str_replace("&lt;titolo/>", $title, $fileHTML);
$fileHTML = str_replace("<contenuto/>", $content, $fileHTML);
$fileHTML = str_replace("<avvisi/>", $warnings, $fileHTML);


// FORM DELETE NEWS
if(isset($_GET['delete_news'])){
  $delete = $_GET['selected_news'];
  $connection->deleteComunication($delete);
  $del='<p aria-role="alert" class="message" id="eliminazione_new">Eliminazione notizia avvenuta con successo</p>';
  $fileHTML = str_replace("<operazione_avvenuta_news/>", $del, $fileHTML);
}

// NEWS LIST CREATION
$news = $connection->viewComunication();
$newsList = '';
if($news != null){
  foreach($news as $n){       
    $newsList .='<option value="'.$n['id'].'">'.$n['title'].'</option>';
  }
}
else
  $newsList = "<p>Nessuna comunicazione presente</p>";


$fileHTML = str_replace("<news/>", $newsList, $fileHTML);



$warnings = '';
$class = '';
$name = '';
$title = '';
$content = '';
$discount_code = '';
$percentage = '5';
$end_date = '';
$minimum = '1';
$message = '';

if(isset($_POST['insert_offer'])){

  if(!empty($_POST['offer__background']) && !empty(clearInput($_POST['offer__title'])) && !empty($_POST['offer__content']) &&
    !empty(clearInput($_POST['offer__discount-code'])) && !empty($_POST['offer__date--end']) && !empty($_POST['offer__discount-percentage']) &&
    $_POST['offer__discount-percentage'] > 1 && !empty($_POST['offer__min-people']) && $_POST['offer__min-people'] >= 1 &&
    (($_POST['offer__category'] != "group") || ($_POST['offer__category'] == "group" && $_POST['offer__min-people'] >= 2))) {

      $connection->addOffer($_POST['offer__category'], $_POST['offer__background'], clearInput($_POST['offer__title']), 
      $_POST['offer__content'], clearInput($_POST['offer__discount-code']), $percentage, $_POST['offer__date--end'], $minimum);
      $message ='<p aria-role="alert" class="message" id="insert_news">Inserimento offerta avvenuto con successo</p>';
  }
  else{ 
    (empty($_POST['offer__title'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--title">Inserisci un titolo</p>' :
      $title = $_POST['offer__title'];
    (empty($_POST['offer__content'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--content">Inserisci una descrizione </p>' :
      $content = $_POST['offer__content'];
    (empty($_POST['offer__discount-code'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--discount-code">Inserisci un codice sconto</p>' :
      $discount_code = $_POST['offer__discount-code'];
    (empty($_POST['offer__discount-percentage']) || $_POST['offer__discount-percentage'] <= 1) ?
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--discount-percentage">Inserisci una percentuale numerica di sconto</p>' :
      $percentage = $_POST['offer__discount-percentage'];
    (empty($_POST['offer__date--end'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--end-date">Inserisci la data fine offerta</p>' :
      $end_date = $_POST['offer__date--end'];
    (empty($_POST['offer__min-people']) || $_POST['offer__min-people'] < 1) ?
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--min-people">Inserisci un numero minimo di persone</p>' :
      $minimum = $_POST['offer__min-people'];
    (empty($_POST['offer__background'])) ?
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--background">Seleziona una immagine</p>' :
      $name = $_POST['offer__background'];
    if($class=="group" && (empty($_POST['offer__min-people']) || $_POST['offer__min-people'] < 2))
      $warnings .='<p aria-role="alert" class="form__error" id="offer__error--group">Inserisci un numero di persone superiore o uguale a 2</p>';
  }
}
$fileHTML = str_replace("<offer__success/>", $message, $fileHTML);
$fileHTML = str_replace("&lt;offer__title/>", $title, $fileHTML);
$fileHTML = str_replace("<offer__content/>", $content, $fileHTML);
$fileHTML = str_replace("&lt;offer__discount-code/>", $discount_code, $fileHTML);
$fileHTML = str_replace("&lt;offer__discount-percentage/>", $percentage, $fileHTML);
$fileHTML = str_replace("&lt;offer__date--end/>", $end_date, $fileHTML);
$fileHTML = str_replace("&lt;offer__min-people/>", $minimum, $fileHTML);
$fileHTML = str_replace("<offer__warnings/>", $warnings, $fileHTML);


// FORM DELETE OFFER
if(isset($_GET['delete_offer'])){
  $delete = $_GET['selected_offer'];
  $connection->deleteOffer($delete);
  $del = '<p aria-role="alert" class="message" id="eliminazione_offerta">Eliminazione offerta avvenuta con successo</p>';
  $fileHTML = str_replace("<operazione_avvenuta_offer/>", $del, $fileHTML);
}


// OFFER LIST CREATION
$offers = $connection->viewOffers();
$offersList = '';
if($offers != null){
  foreach($offers as $offer){ 
    ($name && $offer['title'] == $name) ?
      $offersList .= '<option value="'.$offer['id'].'" selected>'. $offer['class'].' - '.$offer['title'].'</option>' :
      $offersList .= '<option value="'.$offer['id'].'">'. $offer['class'].' - '.$offer['title'].'</option>';
  }
}
else
  $offersList = "<p>Nessuna offerta presente</p>";



$fileHTML = str_replace("<offerte/>", $offersList, $fileHTML);

$connection->closeConnection();

echo $fileHTML;
?>