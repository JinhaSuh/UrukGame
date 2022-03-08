<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\Account;
use PDO;

require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';
require_once __DIR__ . '/../dto/Account.php';
require_once __DIR__ . '/../exception/ExceptionHandler.php';

class AccountRepository
{
    private PDO $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function select_account_list()
    {
        $sql = "SELECT * FROM account";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_account(Account $account)
    {
        $player_id = $account->get_player_id();
        $password = $account->get_password();
        $sql = "SELECT * FROM account WHERE player_id =:playerId AND password=:password";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':playerId', $player_id);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert_account(Account $account)
    {
        $player_id = $account->get_player_id();
        $password = $account->get_password();
        $nation = $account->get_nation();
        $language = $account->get_language();

        $sql = "INSERT INTO account (player_id, password, nation, language) VALUES (:playerId, :password, :nation, :language)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':playerId', $player_id);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nation', $nation);
        $stmt->bindParam(':language', $language);

        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return new \Exception($e);
        }
    }
}

