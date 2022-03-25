<?php

namespace App;

use PDO;

class LogRepository
{
    private PDO $log_db_conn;

    public function __construct()
    {
        $log_db = new LogDatabase();
        $this->log_db_conn = $log_db->getConnection();
    }

    public function create_table(string $table_name){
        $table_list = array("signup_log", "login_log", "departure_log", "arrival_log", "asset_log");

        $table_index = array_search($table_name, $table_list);

        switch($table_index){
            case 0:
                $this->create_signup_table($table_name);
                break;
            case 1:
                $this->create_login_table($table_name);
                break;
            case 2:
                $this->create_departure_table($table_name);
                break;
            case 3:
                $this->create_arrival_table($table_name);
                break;
            case 4:
                $this->create_asset_table($table_name);
                break;
        }
    }

    public function insert_table(string $table_name, $msg){
        $table_list = array("signup_log", "login_log", "departure_log", "arrival_log", "asset_log");

        $table_index = array_search($table_name, $table_list);

        switch($table_index){
            case 0:
                $this->insert_signup_log($table_name, $msg);
                break;
            case 1:
                $this->insert_login_log($table_name, $msg);
                break;
            case 2:
                $this->insert_departure_log($table_name, $msg);
                break;
            case 3:
                $this->insert_arrival_log($table_name, $msg);
                break;
            case 4:
                $this->insert_asset_log($table_name, $msg);
                break;
        }
    }

    public function create_signup_table(string $table_name)
    {
        $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
        log_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        log_time DATETIME NOT NULL,
        user_id INT(11) NOT NULL,
        player_id VARCHAR(45) NOT NULL,
        password VARCHAR(45) NOT NULL,
        nation VARCHAR(45), 
        language VARCHAR(45)
        )";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->execute();
    }

    public function insert_signup_log($table_name, $msg)
    {
        $sql = "INSERT INTO ".$table_name." (
        log_time, user_id, player_id, password, nation, language) VALUES (:logTime, :userId, :playerId, :password, :nation, :language)";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->bindParam(':logTime', $msg->log_time);
        $stmt->bindParam(':userId', $msg->user_id);
        $stmt->bindParam(':playerId', $msg->player_id);
        $stmt->bindParam(':password', $msg->password);
        $stmt->bindParam(':nation', $msg->nation);
        $stmt->bindParam(':language', $msg->language);
        $stmt->execute();
    }

    public function create_login_table(string $table_name)
    {
        $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
        log_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        log_time DATETIME NOT NULL,
        user_id INT(11) NOT NULL,
        player_id VARCHAR(45) NOT NULL,
        password VARCHAR(45) NOT NULL,
        nation VARCHAR(45), 
        language VARCHAR(45)
        )";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->execute();
    }

    public function insert_login_log($table_name, $msg)
    {
        $sql = "INSERT INTO ".$table_name." (
        log_time, user_id, player_id, password, nation, language) VALUES (:logTime, :userId, :playerId, :password, :nation, :language)";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->bindParam(':logTime', $msg->log_time);
        $stmt->bindParam(':userId', $msg->user_id);
        $stmt->bindParam(':playerId', $msg->player_id);
        $stmt->bindParam(':password', $msg->password);
        $stmt->bindParam(':nation', $msg->nation);
        $stmt->bindParam(':language', $msg->language);
        $stmt->execute();
    }

    public function create_departure_table(string $table_name)
    {
        $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
        log_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        log_time DATETIME NOT NULL,
        user_id INT(11) NOT NULL,
        user_state INT(11) NOT NULL,
        level INT(11) NOT NULL,
        exp INT(11) NOT NULL,
        fatigue INT(11) NOT NULL,
        gold INT(11) NOT NULL,
        pearl INT(11) NOT NULL,
        boat_id INT(11) NOT NULL,
        durability INT(11) NOT NULL,
        departure_time DATETIME NOT NULL,
        map_id INT(11) NOT NULL
        )";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->execute();
    }

    public function insert_departure_log($table_name, $msg)
    {
        $sql = "INSERT INTO ".$table_name." (
        log_time, user_id, user_state, level, exp, fatigue, gold, pearl, boat_id, durability, departure_time, map_id) 
        VALUES (:logTime, :userId, :userState, :level, :exp, :fatigue, :gold, :pearl, :boatId, :durability, :departureTime, :mapId)";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->bindParam(':logTime', $msg->log_time);
        $stmt->bindParam(':userId', $msg->user_id);
        $stmt->bindParam(':userState', $msg->user_state);
        $stmt->bindParam(':level', $msg->level);
        $stmt->bindParam(':exp', $msg->exp);
        $stmt->bindParam(':fatigue', $msg->fatigue);
        $stmt->bindParam(':gold', $msg->gold);
        $stmt->bindParam(':pearl', $msg->pearl);
        $stmt->bindParam(':boatId', $msg->boat_id);
        $stmt->bindParam(':durability', $msg->durability);
        $stmt->bindParam(':departureTime', $msg->departure_time);
        $stmt->bindParam(':mapId', $msg->map_id);
        $stmt->execute();
    }

    public function create_arrival_table(string $table_name)
    {
        $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
        log_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        log_time DATETIME NOT NULL,
        user_id INT(11) NOT NULL,
        user_state INT(11) NOT NULL,
        boat_id INT(11) NOT NULL,
        durability INT(11) NOT NULL,
        departure_time DATETIME NOT NULL,
        map_id INT(11) NOT NULL
        )";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->execute();
    }

    public function insert_arrival_log($table_name, $msg)
    {
        $sql = "INSERT INTO ".$table_name." (
        log_time, user_id, user_state, boat_id, durability, departure_time, map_id) 
        VALUES (:logTime, :userId, :userState, :boatId, :durability, :departureTime, :mapId)";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->bindParam(':logTime', $msg->log_time);
        $stmt->bindParam(':userId', $msg->user_id);
        $stmt->bindParam(':userState', $msg->user_state);
        $stmt->bindParam(':boatId', $msg->boat_id);
        $stmt->bindParam(':durability', $msg->durability);
        $stmt->bindParam(':departureTime', $msg->departure_time);
        $stmt->bindParam(':mapId', $msg->map_id);
        $stmt->execute();
    }

    public function create_asset_table(string $table_name)
    {
        $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
        log_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        log_time DATETIME NOT NULL,
        user_id INT(11) NOT NULL,
        level INT(11) NOT NULL,
        exp INT(11) NOT NULL,
        fatigue INT(11) NOT NULL,
        gold INT(11) NOT NULL,
        pearl INT(11) NOT NULL,
        item_type_id INT(11) NOT NULL,
        count INT(11) NOT NULL,
        action VARCHAR(45) NOT NULL
        )";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->execute();
    }

    public function insert_asset_log($table_name, $msg)
    {
        $sql = "INSERT INTO ".$table_name." (
        log_time, user_id, level, exp, fatigue, gold, pearl, item_type_id, count, action) 
        VALUES (:logTime, :userId, :level, :exp, :fatigue, :gold, :pearl, :itemTypeId, :count, :action)";

        $stmt = $this->log_db_conn->prepare($sql);
        $stmt->bindParam(':logTime', $msg->log_time);
        $stmt->bindParam(':userId', $msg->user_id);
        $stmt->bindParam(':level', $msg->level);
        $stmt->bindParam(':exp', $msg->exp);
        $stmt->bindParam(':fatigue', $msg->fatigue);
        $stmt->bindParam(':gold', $msg->gold);
        $stmt->bindParam(':pearl', $msg->pearl);
        $stmt->bindParam(':itemTypeId', $msg->item_type_id);
        $stmt->bindParam(':count', $msg->count);
        $stmt->bindParam(':action', $msg->action);
        $stmt->execute();
    }

    //TODO : 파일 -> DB 할 때 파일의 내용(일부분이라도)이 이미 DB에 있다면 DB를 지우고 새로 INSERT
}
