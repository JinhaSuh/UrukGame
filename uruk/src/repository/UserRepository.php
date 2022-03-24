<?php

namespace App\repository;

use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use App\exception\InvalidError;
use App\exception\UnknownUser;
use PDO;

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
     * @throws UnknownUser
     */
    public function select_user($user)
    {
        $user_id = $user["user_id"];
        $sql = "SELECT * FROM user WHERE user_id =:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new UnknownUser();
    }

    /**
     * @throws UnknownUser
     */
    public function insert_user($user)
    {
        $sql = "INSERT INTO user (user_id, user_name, level, exp, fatigue, gold, pearl) VALUES (:userId, :userName, :level, :exp, :fatigue, :gold, :pearl)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user->user_id);
        $stmt->bindParam(':userName', $user->user_name);
        $stmt->bindParam(':level', $user->level);
        $stmt->bindParam(':exp', $user->exp);
        $stmt->bindParam(':fatigue', $user->fatigue);
        $stmt->bindParam(':gold', $user->gold);
        $stmt->bindParam(':pearl', $user->pearl);

        $stmt->execute();

        $new_user = ['user_id' => $user->user_id];
        return $this->select_user($new_user);
    }

    /**
     * @throws InvalidError
     */
    public function update_user($user)
    {
        $user_id = $user["user_id"];
        $user_name = $user["user_name"];
        $level = $user["level"];
        $exp = $user["exp"];
        $fatigue = $user["fatigue"];
        $gold = $user["gold"];
        $pearl = $user["pearl"];
        $sql = "UPDATE user SET user_name=:userName, level=:level, exp=:exp, fatigue=:fatigue, gold=:gold, pearl=:pearl WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':userName', $user_name);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':exp', $exp);
        $stmt->bindParam(':fatigue', $fatigue);
        $stmt->bindParam(':gold', $gold);
        $stmt->bindParam(':pearl', $pearl);

        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $user;
        else
            throw new InvalidError();
    }

    /**
     * @throws UnknownUser
     */
    public function update_user_state(int $user_id, int $state, int $depth)
    {
        $sql = "UPDATE user_state SET state=:state, depth=:depth WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':depth', $depth);

        $stmt->execute();
        return $this->select_user_state($user_id);
    }

    /**
     * @throws UnknownUser
     */
    public function select_user_state(int $user_id)
    {
        $sql = "SELECT * FROM user_state WHERE user_id =:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new UnknownUser();
    }

    public function insert_user_state(int $user_id, int $state, int $depth)
    {
        $sql = "INSERT INTO user_state (user_id, state, depth) VALUES (:userId, :state, :depth)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':depth', $depth);

        $stmt->execute();
    }

    /**
     * @throws InvalidError
     */
    public function select_level_data(int $level)
    {
        $sql = "SELECT * FROM level_data WHERE level =:level";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':level', $level);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch();
        else
            throw new InvalidError();
    }
}
