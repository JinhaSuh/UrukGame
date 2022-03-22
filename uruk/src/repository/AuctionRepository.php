<?php

namespace App\repository;

use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use App\exception\UnknownFish;
use PDO;

class AuctionRepository
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

    public function select_auction(int $user_id)
    {
        $sql = "SELECT * FROM auction WHERE user_id =:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_auction_fish(int $user_id, int $tank_id)
    {
        $sql = "SELECT * FROM auction WHERE user_id =:userId AND tank_id=:tankId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':tankId', $tank_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @throws UnknownFish
     */
    public function select_auction_with_aucId(int $user_id, int $auc_id)
    {
        $sql = "SELECT * FROM auction WHERE user_id =:userId AND auc_id=:aucId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':aucId', $auc_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else throw new UnknownFish();
    }

    public function insert_auction_fish(int $user_id, int $tank_id, int $price)
    {
        $sql = "INSERT INTO auction (user_id, tank_id, price) VALUES (:userId, :tankId, :price)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':tankId', $tank_id);
        $stmt->bindParam(':price', $price);

        $stmt->execute();
    }

    public function select_auction_data(int $fish_id)
    {
        $sql = "SELECT * FROM auction_data WHERE fish_id =:fishId";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':fishId', $fish_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete_auction_fish(int $user_id, int $auc_id)
    {
        $sql = "DELETE FROM auction WHERE user_id=:userId AND auc_id =:aucId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':aucId', $auc_id);

        $stmt->execute();
        return $this->select_auction($user_id);
    }
}
