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

    public function closeConnection(){
        mysqli_close($this -> connection);
    }
}