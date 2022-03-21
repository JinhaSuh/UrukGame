<?php

namespace App\repository;

use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use PDO;

class CollectionRepository
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

    public function select_collection(int $user_id)
    {
        $sql = "SELECT * FROM collection WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_collection_fish(int $user_id, int $fish_id)
    {
        $sql = "INSERT INTO collection (user_id, fish_id) VALUES (:userId, :fishId)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':fishId', $fish_id);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_collection_fish(int $user_id, int $fish_id)
    {
        $sql = "SELECT * FROM collection WHERE user_id=:userId AND fish_id=:fishId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':fishId', $fish_id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
