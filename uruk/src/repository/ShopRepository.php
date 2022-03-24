<?php

namespace App\repository;

use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use PDO;

class ShopRepository
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

    public function select_shop_data()
    {
        $sql = "SELECT * FROM shop_item_data";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_shop(int $user_id)
    {
        $sql = "SELECT * FROM shop WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_shop_item(int $user_id, int $shop_id)
    {
        $sql = "SELECT * FROM shop WHERE user_id=:userId AND shop_id=:shopId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':shopId', $shop_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete_user_shop(int $user_id)
    {
        $sql = "DELETE FROM shop WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->execute();
    }

    public function insert_user_shop(int $user_id, int $item_type_id, int $item_id, int $item_count, int $need_item_type_id, int $need_count)
    {
        $sql = "INSERT INTO shop (user_id, item_type_id, item_id, item_count, need_item_type_id, need_count) VALUES (:userId, :itemTypeId, :itemId, :itemCount, :needItemTypeId, :needCount)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':itemTypeId', $item_type_id);
        $stmt->bindParam(':itemId', $item_id);
        $stmt->bindParam(':itemCount', $item_count);
        $stmt->bindParam(':needItemTypeId', $need_item_type_id);
        $stmt->bindParam(':needCount', $need_count);
        $stmt->execute();
    }

}
