<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\Account;
use exception\AccountException;
use PDO;

require_once __DIR__ . '/../dto/Account.php';
require_once __DIR__ . '/../exception/AccountException.php';
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
     * @throws AccountException
     */
    public function select_account(Account $account)
    {
        $player_id = $account->get_player_id();
        $password = $account->get_password();
        $sql = "SELECT * FROM account WHERE player_id =:playerId AND password=:password";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':playerId', $player_id);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
        if ($stmt->rowCount() > 0)
            return $stmt->fetchObject(Account::class);
        else
            throw new AccountException("알 수 없는 HiveID입니다.", 507);
    }

    /**
     * @throws AccountException
     */
    public function insert_account(Account $account)
    {
        $player_id = $account->get_player_id();
        $password = $account->get_password();
        isset($account->nation) ? $nation = $account->get_nation() : $nation = null;
        isset($account->language) ? $language = $account->get_language() : $language = null;

        $sql = "INSERT INTO account (player_id, password, nation, language) VALUES (:playerId, :password, :nation, :language)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':playerId', $player_id);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nation', $nation);
        $stmt->bindParam(':language', $language);

        $stmt->execute();
        $new_user_id = $this->game_db_conn->lastInsertId();

        $new_account = new Account();
        $new_account->player_id = $account->get_player_id();
        $new_account->password = $account->get_password();
        if (!isset($account->nation)) $new_account->nation = $account->nation = "";
        if (!isset($account->language)) $new_account->language = $account->language = "";
        $new_account->user_id = $new_user_id;

        return $new_account;
    }
}

