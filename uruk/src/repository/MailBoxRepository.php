<?php

namespace App\repository;

use App\db\Game_Data_Database;
use App\db\Plan_Data_Database;
use App\exception\UnknownMail;
use App\exception\UnknownUser;
use PDO;

class MailBoxRepository
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
    public function select_mailBox(int $user_id, string $now_date)
    {
        $sql = "SELECT * FROM mailbox WHERE user_id =:userId AND expr_date>=:nowDate";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':nowDate', $now_date);

        $stmt->execute();
        if ($stmt->rowCount() >= 0)
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        else
            throw new UnknownUser();
    }

    /**
     * @throws UnknownMail
     */
    public function select_mailBox_item(int $user_id, int $mail_id, string $now_date)
    {
        $sql = "SELECT * FROM mailbox WHERE user_id =:userId AND mail_id=:mailId AND expr_date>=:nowDate";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':mailId', $mail_id);
        $stmt->bindParam(':nowDate', $now_date);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new UnknownMail();
    }

    /**
     * @throws UnknownUser
     */
    public function delete_mailBox_item(int $user_id, int $mail_id)
    {
        $sql = "DELETE FROM mailbox WHERE user_id =:userId AND mail_id=:mailId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':mailId', $mail_id);

        $stmt->execute();

        return $this->select_mailBox($user_id, date("Y-m-d H:i:s"));
    }
}
