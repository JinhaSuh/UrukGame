<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\User;
use PDO;

require_once __DIR__ . '/../Game_Data_Database.php';
require_once __DIR__ . '/../Plan_Data_Database.php';
require_once __DIR__ . '/../dto/User.php';

class UserRepository
{
    private PDO $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function select_user_list()
    {
        $sql = "SELECT * FROM user";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_user(User $user)
    {
        $user_id = $user->get_user_id();
        $sql = "SELECT * FROM user WHERE user_id =:userId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
