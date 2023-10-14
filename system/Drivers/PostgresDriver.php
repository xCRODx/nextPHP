<?php 

namespace Drivers;

//use PDO;
use PDOException;
use Interfaces\BaseDbEngine;
use Exceptions\DB_Exception;

class PostgresDriver implements BaseDbEngine {

    function __construct(){}
    public function query($sql){

    }

    public function connect(){
        try{
            $conn = new \PDO("pgsql:host=".env('db_host').";port=".env('db_port').";dbname=".env('db_name'), env('db_user'), env('db_pass'));
            if($conn) return $conn;
            
        }catch (PDOException $e){
            throw new DB_Exception(sprintf(t("Erro ao realizar consulta ao banco de dados.").S, $e->getMessage()));
        } 
            
    }
}