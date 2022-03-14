<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\User;
use exception\UserException;
use PDO;

require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';

class UserRepository
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

    public function select_user_list()
    {
        $sql = "SELECT * FROM user";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws UserException
     */
    public function select_user(User $user)
    {
        $user_id = $user->get_user_id();
        $sql = "SELECT * FROM user WHERE user_id =:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchObject(User::class);
        else
            throw new UserException("알 수 없는 유저입니다.", 507);
    }

    /**
     * @throws UserException
     */
    public function update_user(User $user)
    {
        $user_id = $user->get_user_id();
        $fatigue = $user->get_fatigue();
        $gold = $user->get_gold();
        $sql = "UPDATE user SET fatigue=:fatigue, gold=:gold WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':fatigue', $fatigue);
        $stmt->bindParam(':gold', $gold);

        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $user;
        else
            throw new UserException("알 수 없는 유저입니다.", 507);
    }

    /**
     * @throws UserException
     */
    public function select_level_data(int $level)
    {
        $sql = "SELECT * FROM level_up_rule_data WHERE level =:level";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':level', $level);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch();
        else
            throw new UserException("알 수 없는 유저입니다.", 507);
    }
}
