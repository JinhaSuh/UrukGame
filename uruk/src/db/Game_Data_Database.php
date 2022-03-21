<?php

namespace App\db;

use PDO;
use PDOException;

class Game_Data_Database
{
    private $host = "localhost:3306";
    private $db_name = "GAME_DATA";
    private $username = "root";
    private $password = "Xowlsgk01!";

    public function getConnection()
    {
        try {
            $conn_str = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $conn = new PDO($conn_str, $this->username, $this->password);
            $conn->exec("set names utf8mb4");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $conn;
    }
}
