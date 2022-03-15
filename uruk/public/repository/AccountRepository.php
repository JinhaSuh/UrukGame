<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\Account;
use exception\UnknownHiveID;
use PDO;

require_once __DIR__ . '/../dto/Account.php';
require_once __DIR__ . '/../exception/UnknownHiveID.php';
require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';

class AccountRepository
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

    public function select_account_list()
    {
        $sql = "SELECT * FROM account";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws UnknownHiveID
     */
    public function select_account($account)
    {
        $player_id = $account["player_id"];
        $password = $account["password"];
        $sql = "SELECT * FROM account WHERE player_id =:playerId AND password=:password";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':playerId', $player_id);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC);
        else
            throw new UnknownHiveID();
    }

    /**
     * @throws UnknownHiveID
     */
    public function insert_account($account)
    {
        $player_id = $account["player_id"];
        $password = $account["password"];
        isset($account["nation"]) ? $nation = $account["nation"] : $nation = null;
        isset($account["language"]) ? $language = $account["language"] : $language = null;

        $sql = "INSERT INTO account (player_id, password, nation, language) VALUES (:playerId, :password, :nation, :language)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':playerId', $player_id);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nation', $nation);
        $stmt->bindParam(':language', $language);
        $stmt->execute();

        return $this->select_account($account);
    }
}

