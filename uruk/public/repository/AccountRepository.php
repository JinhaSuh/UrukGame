<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\Account;
use exception\AccountException;
use PDO;

require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';
require_once __DIR__ . '/../dto/Account.php';

class AccountRepository
{
    private PDO $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        //set_exception_handler('account_exception');
    }

    public function select_account_list()
    {
        $sql = "SELECT * FROM account";

        $stmt = $this->conn->prepare($sql);
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

        $stmt = $this->conn->prepare($sql);
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

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':playerId', $player_id);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nation', $nation);
        $stmt->bindParam(':language', $language);

        $stmt->execute();
        return $account;
    }
}
