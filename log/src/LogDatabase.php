<?php

namespace App;

use PDO;
use PDOException;

class LogDatabase
{
    private $host = "192.168.152.1:3306";
    private $db_name = "LOG_DATA";
    private $username = "root";
    private $password = "Xowlsgk01!";

    public function getConnection()
    {
        try {
            $conn_str = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $conn = new PDO($conn_str, $this->username, $this->password);
            $conn->exec("set names utf8"); // 설정을 안해주면 한글깨짐.
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $conn;
    }
}
