<?php
namespace DB\Config;
use PDO;
use PDOException;

class Database{
    private $host = "localhost:3306";
    private $db_name = "study_db";
    private $username = "root";
    private $password = "xowlsgk01!";

    public function getConnection(){
        try {
            $conn_str = "mysql:host=".$this->host.";dbname=".$this->db_name;
            $pdo = new PDO($conn_str, $this->username, $this->password);
            $pdo->exec("set names utf8");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }catch (PDOException $exception){
            echo "Connection error: ".$exception->getMessage();
        }

        return $pdo;
    }
}
