<?php

namespace App\repository;

use App\dto\FishInfo;
use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use PDO;

class WaterTankRepository
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

    public function select_water_tank(int $user_id)
    {
        $sql = "SELECT * FROM water_tank WHERE user_id =:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_water_tank_fish(int $user_id, FishInfo $fish)
    {
        $sql = "INSERT INTO water_tank (user_id, fish_id, length, caught_time) VALUES (:userId, :fishId, :length, :caughtTime)";
        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':fishId', $fish->fish_id);
        $stmt->bindParam(':length', $fish->length);
        $date = date("Y-m-d H:i:s", $fish->caught_time->getTimestamp());
        $stmt->bindParam(':caughtTime', $date);

        $stmt->execute();

        return $this->select_water_tank($user_id);
    }

    public function delete_water_tank_fish(int $user_id, int $tank_id)
    {
        $sql = "DELETE FROM water_tank WHERE user_id=:userId AND tank_id =:tankId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':tankId', $tank_id);

        $stmt->execute();

        return $this->select_water_tank($user_id);
    }
}
