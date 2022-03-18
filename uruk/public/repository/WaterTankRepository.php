<?php

namespace repository;

use dto\FishInfo;
use exception\UserException;
use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use PDO;

require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';
require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../dto/FishInfo.php';

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

    /**
     * @throws UserException
     */
    public function select_water_tank(int $user_id)
    {
        $sql = "SELECT * FROM water_tank WHERE user_id =:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        else
            throw new UserException();
    }

    /**
     * @throws UserException
     */
    public function insert_water_tank(int $user_id, FishInfo $fish)
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
}
