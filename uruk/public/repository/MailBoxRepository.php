<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use exception\UnknownMail;
use exception\UserException;
use PDO;

require_once __DIR__ . '/../exception/UnknownMail.php';
require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';

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
     * @throws UserException
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
            throw new UserException();
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
     * @throws UserException
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
