<?php
session_start();

require_once "DBAccess.php"; 

$fileHTML = file_get_contents("comunications.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a href="./login.php" class="nav__link">Area Riservata</a>', $fileHTML);
if (isset($_SESSION['user'])) $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="userpage.php" lang="en-US">Account</a>', $fileHTML);
if (isset($_SESSION['admin'])) $fileHTML = str_replace("<navbar/>", '<a class="nav__link" href="adminpage.php" lang="en-US">Account</a>', $fileHTML);

$comunicazioni= $connessione->getData("news where initial_date>= CURDATE() order by initial_date ");
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

        $content = str_replace('#i', '<time datetime='.date('Y-m-d', $init_date).'>'.$f_init_date.'</time>', $comunicazione['content']);
        $content = str_replace('#f', '<time datetime='.date('Y-m-d', $end_date).'>'.$f_final_date.'</time>', $content);

        $newsList .='<article class="news js-news">
        .<time class="news__date" datetime="'.date('Y-m-d', $init_date).'">
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
echo $fileHTML;
?>