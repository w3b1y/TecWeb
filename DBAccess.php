<?php
namespace DB;
class DBAccess{
    private const HOST_B = "localhost";
    private const DATABASE_NAME = "iberu_transportation";
    private const USERNAME = "iberu_transportation";
    private const PASSWORD = "iberu_transportation";
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
        $qResult = mysqli_query($this->connection, $query) or die("query fallita".mysqli_error($this->connection));

        if(mysqli_num_rows($qResult) == 1){
            $result = mysqli_fetch_assoc($qResult);
            $qResult->free();
            return $result;
        }
        else{
            return null;
        }
    }

    public function closeConnection(){
        mysqli_close($this -> connection);
    }
}