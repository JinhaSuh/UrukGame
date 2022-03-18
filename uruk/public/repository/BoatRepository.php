<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\Boat;
use exception\MaxGrade;
use exception\UserException;
use PDO;

require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../exception/MaxGrade.php';
require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';
require_once __DIR__ . '/../dto/Boat.php';

class BoatRepository
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
    public function select_boat(int $user_id)
    {
        $sql = "SELECT * FROM boat WHERE user_id =:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new UserException();
    }

    /**
     * @throws MaxGrade
     */
    public function select_boat_upgrade_data(int $boat_id)
    {
        $sql = "SELECT * FROM boat_upgrade_data WHERE step_id =:boatId";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':boatId', $boat_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new MaxGrade();
    }


    /**
     * @throws MaxGrade
     */
    public function select_boat_data(int $boat_id)
    {
        $sql = "SELECT * FROM boat_data WHERE boat_id =:boatId";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':boatId', $boat_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new MaxGrade();
    }

    /**
     * @throws UserException
     */
    public function update_boat(int $user_id, Boat $boat)
    {
        $sql = "UPDATE boat SET boat_id=:boatId, durability=:durability, fuel=:fuel, departure_time=:departureTime, map_id=:mapId WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':boatId', $boat->boat_id);
        $stmt->bindParam(':durability', $boat->durability);
        $stmt->bindParam(':fuel', $boat->fuel);
        $date = date("Y-m-d H:i:s", $boat->departure_time->getTimestamp());
        $stmt->bindParam(':departureTime', $date);
        $stmt->bindParam(':mapId', $boat->map_id);

        $stmt->execute();

        return $this->select_boat($user_id);
    }
}
