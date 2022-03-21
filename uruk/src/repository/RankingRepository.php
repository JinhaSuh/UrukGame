<?php

namespace App\repository;

use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use App\exception\UnknownUser;
use PDO;

class RankingRepository
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
     * @throws UnknownUser
     */
    public function select_ranking()
    {
        $sql = "SELECT * FROM ranking ORDER BY gold_sum";

        $stmt = $this->game_db_conn->prepare($sql);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        else
            throw new UnknownUser();
    }

    public function select_user_ranking(int $user_id)
    {
        $sql = "SELECT * FROM ranking WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_ranking(int $user_id, string $date, int $gold_sum)
    {
        $sql = "UPDATE ranking SET gold_sum=:goldSum, week_date=:weekDate WHERE user_id=:userId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':weekDate', $date);
        $stmt->bindParam(':goldSum', $gold_sum);

        $stmt->execute();
    }

    public function insert_ranking(int $user_id, string $date, int $gold_sum)
    {
        $sql = "INSERT INTO ranking (user_id, week_date, gold_sum) VALUES (:userId, :weekDate, :goldSum)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':weekDate', $date);
        $stmt->bindParam(':goldSum', $gold_sum);

        $stmt->execute();
    }
}
