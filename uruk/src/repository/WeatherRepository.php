<?php

namespace App\repository;

use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use PDO;

class WeatherRepository
{
    private PDO $game_db_conn;
    private PDO $plan_db_conn;

    public function __construct()
    {
        $game_db = new Game_Data_Database();
        $plan_db = new Plan_Data_Database();
        $this->game_db_conn = $game_db->getConnection();
        $this->plan_db_conn = $plan_db->getConnection();
    }

    public function select_wind_data()
    {
        $sql = "SELECT * FROM wind_data";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function select_temperature_data()
    {
        $sql = "SELECT * FROM temperature_data";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function select_tide_data()
    {
        $sql = "SELECT * FROM tide_data";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
