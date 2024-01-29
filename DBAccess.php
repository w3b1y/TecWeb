<?php
namespace DB;
class DBAccess{
    private const HOST_B = "localhost";
    private const DATABASE_NAME = "iberu";
    private const USERNAME = "root";
    private const PASSWORD = "";
    private $connection;

    public function openDBConnection(){
        $this -> connection = mysqli_connect(
            self::HOST_B,
            self::USERNAME,
            self::PASSWORD,
            self::DATABASE_NAME
        );
        if(mysqli_connect_errno()){
            echo "connessione non riuscita";
            return;
        }
    }

    public function getData(string $Tname){
        $query= "SELECT * FROM $Tname";
        $qResult = mysqli_query($this->connection, $query) or die("query fallita".mysqli_error($this->connection));

        if(mysqli_num_rows($qResult)==0){
            return null;
        }
        else{
            $result = array();
            while($riga = mysqli_fetch_assoc($qResult)){
                array_push($result, $riga);
            }
            $qResult->free();
            return $result;
        }
    }

    public function addData(string $query){
        $qResult = mysqli_query($this->connection, $query) or die("query fallita".mysqli_error($this->connection));
        mysqli_query($this->connection, $query) or die("Errore nell'inserimento della notizia $titolo ".mysqli_error($this->connection));
        return;
    }

    public function getDataArray(string $query){
        $qResult = mysqli_query($this->connection, $query) or die("Query fallita: " . mysqli_error($this->connection));
    
        if(mysqli_num_rows($qResult) == 0){
            return null;
        }
        else{
            $result = array();
            while($riga = mysqli_fetch_assoc($qResult)){
                // Se c'è solo una colonna, restituisce direttamente il valore anziché l'array
                if (count($riga) == 1) {
                    $result[] = reset($riga);
                } else {
                    $result[] = $riga;
                }
            }
            return $result;
            $qResult->free();
        }
    }
    
    public function checkStazione($stazione){
        $query = "SELECT name FROM station
                WHERE (name = \"$stazione\")";
        $qResult = mysqli_query($this->connection, $query) or die("Errore nel controllo della stazione $stazione".mysqli_error($this->connection));

        if(mysqli_num_rows($qResult) == 1){
            $result = mysqli_fetch_assoc($qResult);
            $qResult->free();
            return $result;
        }
        else{
            return null;
        }
    }

    public function addComunication($data_i, $data_f, $titolo, $contenuto){
        $query = "INSERT INTO news VALUES (NULL, \"$titolo\", \"$contenuto\", \"$data_i\", \"$data_f\")";
        mysqli_query($this->connection, $query) or die("Errore nell'inserimento della notizia $titolo ".mysqli_error($this->connection));
    }

    public function viewComunication(){
        $query= "SELECT id, title FROM news";
        $qResult = mysqli_query($this->connection, $query) or die("Errore ricerca notizie ".mysqli_error($this->connection));

        if(mysqli_num_rows($qResult)==0){
            return null;
        }
        else{
            $result = array();
            while($riga = mysqli_fetch_assoc($qResult)){
                array_push($result, $riga);
            }
            $qResult->free();
            return $result;
        }}

    public function deleteComunication($id){
        $query = "DELETE FROM news WHERE id=\"$id\"";
        mysqli_query($this->connection, $query) or die("Errore nell'eliminazione della notizia ".mysqli_error($this->connection));
        //return null;
    }

    public function addOffer($classe, $nome, $titolo, $contenuto, $codice_sconto, $percentuale, $data_fine, $minimo, $img){
        $query = "INSERT INTO offers VALUES (NULL, \"$classe\", \"$nome\", \"$titolo\", \"$contenuto\", \"$codice_sconto\", \"$data_fine\", \"$percentuale\", \"$img\", \"$minimo\")";
        mysqli_query($this->connection, $query) or die("Errore nell'inserimento dell'offerta $titolo ".mysqli_error($this->connection));
    }

    public function viewOffers(){
        $query= "SELECT id, class, nome, title FROM offers";
        $qResult = mysqli_query($this->connection, $query) or die("Errore ricerca offerte ".mysqli_error($this->connection));

        if(mysqli_num_rows($qResult)==0){
            return null;
        }
        else{
            $result = array();
            while($riga = mysqli_fetch_assoc($qResult)){
                array_push($result, $riga);
            }
            $qResult->free();
            return $result;
        }
    }

    public function deleteOffer($id){
        $query = "DELETE FROM offers WHERE id=\"$id\"";
        mysqli_query($this->connection, $query) or die("Errore nell'eliminazione dell'offerta' ".mysqli_error($this->connection));
        //return null;
    }

    public function addUser($nome, $cognome, $email, $data_nascita, $np){
        $query = "INSERT INTO user VALUES (NULL, \"$nome\", \"$cognome\", \"$email\", \"$np\", \"$data_nascita\")";
        mysqli_query($this->connection, $query) or die("Errore nella registrazione dell'utente $nome $cognome ".mysqli_error($this->connection));
    }

    public function closeConnection(){
        mysqli_close($this -> connection);
    }
}