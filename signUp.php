<?php
session_start();

require_once "DBAccess.php"; 
require_once "funzioni.php"; 

$fileHTML = file_get_contents("signUp.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();

$avvisi='';
$avvisi_p='';

//variabili per il form
$nome = '';
$cognome = '';
$email = '';
$data_nascita = '';

//al click di new_user
if(isset($_POST['new_user'])){
    $nome = clearInput($_POST['name']);
    $cognome = clearInput($_POST['surname']);
    $email = clearInput($_POST['email']);
    $data_nascita = $_POST['birthday'];
    $np = clearInput($_POST['new_password']);
    $rnp = clearInput($_POST['rnew_password']);

    if(!empty($nome) && !empty($cognome) && !empty($email) && !empty($data_nascita) && !empty($np) && !empty($rnp) && $np==$rnp){
        $connessione->addUser($nome, $cognome, $email, $data_nascita, $np);
        $ins='<p class="form__error" id="registrazione_avvenuta">Registrazione avvenuta con successo</p>';
        $fileHTML = str_replace("<registrazione_avvenuta/>", $ins, $fileHTML);
        $nome = '';
        $cognome = '';
        $email = '';
        $data_nascita = '';
        //wait 10 secondi e poi direttamente pagina_login
    }
    else{
        if(empty($nome)){
            $avvisi .='<p class="form__error" id="name_empty">Inserire nome</p>';
        } 
        if(empty($cognome)){
            $avvisi .='<p class="form__error" id="surname_empty">Inserire cognome</p>';
        } 
        if(empty($email)){
            $avvisi .= '<p class="form__error" id="email_empty">Inserire mail</p>';
        }
        if(empty($data_nascita)){
            $avvisi .='<p class="form__error" id="birthday_empty">Inserire data di nascita</p>';
        } 
        if(empty($np)){
            $avvisi_p .='<p class="form__error" id="birthday_empty">Inserire una password</p>';
        }
        if(empty($rnp)){
            $avvisi_p .='<p class="form__error" id="birthday_empty">Reinserire la password</p>';
        }     
        if($np!=$rnp){
            $avvisi_p .='<p class="form__error" id="birthday_empty">Le password non corrispondono</p>';
        } 
    }
}
$fileHTML = str_replace("&lt;nome/>", $nome, $fileHTML);
$fileHTML = str_replace("&lt;cognome/>", $cognome, $fileHTML);
$fileHTML = str_replace("&lt;email/>", $email, $fileHTML);
$fileHTML = str_replace("&lt;data_nascita/>", $data_nascita, $fileHTML);
$fileHTML = str_replace("<avvisi/>", $avvisi, $fileHTML);
$fileHTML = str_replace("<avvisi_p/>", $avvisi_p, $fileHTML);

$connessione->closeConnection();
echo $fileHTML;
?>