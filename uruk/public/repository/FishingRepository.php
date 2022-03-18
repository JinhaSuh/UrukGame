<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use exception\InvalidWaterDepth;
use exception\UnknownFish;
use exception\UnknownMap;
use PDO;

require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';
require_once __DIR__ . '/../exception/UnknownMap.php';
require_once __DIR__ . '/../exception/InvalidWaterDepth.php';
require_once __DIR__ . '/../exception/UnknownFish.php';

class FishingRepository
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
     * @throws InvalidWaterDepth
     */
    public function select_map_fish_drop_data(int $map_id, int $depth)
    {
        $sql = "SELECT * FROM map_fish_drop_data WHERE map_id =:mapId AND min_water_depth<=:depth AND max_water_depth>=:depth";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':mapId', $map_id);
        $stmt->bindParam(':depth', $depth);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        else
            throw new InvalidWaterDepth();
    }

    /**
     * @throws UnknownMap
     */
    public function select_map_item_drop_data(int $map_id)
    {
        $sql = "SELECT * FROM map_item_drop_data WHERE map_id =:mapId";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':mapId', $map_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        else
            throw new UnknownMap();
    }

    /**
     * @throws UnknownFish
     */
    public function select_fish_data(int $fish_id)
    {
        $sql = "SELECT * FROM fish_data WHERE fish_id =:fishId";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':fishId', $fish_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new UnknownFish();
    }
}
