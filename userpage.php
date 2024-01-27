<?php
session_start();

$fileHTML = file_get_contents("userpage.html");

use DB\DBAccess;
$connessione = new DBAccess();
$connessione->openDBConnection();

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

$ticket = '<article class="ticket">
            <dl class="ticket__route--horizontal">
              <dt class="route__term--horizontal">Padova</dt>
              <dd class="route__data--horizontal"><time datetime="10:00">10:00</time></dd>
              <dt class="route__term--horizontal route__term--line">Durata</dt>
              <dd class="route__data--horizontal route__data--line">30 minuti</dd>
              <dt class="route__term--horizontal">Venezia</dt>
              <dd class="route__data--horizontal"><time datetime="10:30">10:30</time></dd>
            </dl>
            <div class="container ticket__description">
              <p class="ticket__content">Data: <time datetime="">15 Febbraio 2024 16:37</time></p>
              <p class="ticket__content">Tipologia tratta: Diretto</p>
              <p class="ticket__content">Identificativo treno: rv0000</p>
              <p class="ticket__class js-first__class ticket__class--selected">Prima classe</p>
            </div>
            </article>';
?>