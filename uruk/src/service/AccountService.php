<?php

namespace App\service;

use App\dto\Equipment;
use App\dto\User;
use App\dto\Boat;
use App\exception\InvalidRequestBody;
use App\exception\UnknownHiveID;
use App\exception\UnknownUser;
use App\repository\AccountRepository;
use App\repository\BoatRepository;
use App\repository\InventoryRepository;
use App\repository\ScribeRepository;
use App\repository\UserRepository;

class AccountService
{
    private AccountRepository $accountRepository;
    private UserRepository $userRepository;
    private BoatRepository $boatRepository;
    private InventoryRepository $inventoryRepository;
    private ScribeRepository $scribeRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->userRepository = new UserRepository();
        $this->boatRepository = new BoatRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->scribeRepository = new ScribeRepository();
    }

    /**
     * @throws UnknownHiveID
     * @throws InvalidRequestBody
     */
    public function select_account($account)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($account["player_id"]) || !isset($account["password"])) {
            throw new InvalidRequestBody();
        }

        $select_account = $this->accountRepository->select_account($account);
        //scribe - login
        $msg = [
            "user_id" => $select_account["user_id"],
            "player_id" => $select_account["player_id"],
            "password" => $select_account["password"],
            "nation" => $select_account["nation"],
            "language" => $select_account["language"]
        ];
        $this->scribeRepository->Log("login", $msg);

        return $select_account;
    }

    /**
     * @throws InvalidRequestBody|UnknownHiveID|UnknownUser
     */
    public function insert_account($account)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($account["player_id"]) || !isset($account["password"])) {
            throw new InvalidRequestBody();
        }

        $select_account = $this->accountRepository->select_account_by_player_id($account);
        if (empty($select_account)) $new_account = $this->accountRepository->insert_account($account);
        else throw new UnknownHiveID();

        //유저 초기화
        $user = new User();
        $user->set_user_id($new_account["user_id"]);
        $user->set_username("유저" . $new_account["user_id"] . "번");
        $user->set_level(1);
        $user->set_exp(0);
        $user->set_fatigue(10);
        $user->set_gold(500);
        $user->set_pearl(5);
        $new_user = $this->userRepository->insert_user($user);

        //유저 상태 초기화
        $this->userRepository->insert_user_state($new_user["user_id"], 0, 0);

        //배 정보 초기화
        $boat = new Boat();
        $boat->set_map_id(0);
        $boat->set_departure_time(new \DateTime(date("Y-m-d H:i:s")));
        $boat->set_fuel(50);
        $boat->set_durability(30);
        $boat->set_boat_id(1);
        $new_boat = $this->boatRepository->insert_boat($new_user["user_id"], $boat);

        //채비 초기화
        $this->insert_default_equipments($new_user["user_id"]);

        //scribe - signup
        $msg = [
            "user_id" => $new_account["user_id"],
            "player_id" => $new_account["player_id"],
            "password" => $new_account["password"],
            "nation" => $new_account["nation"],
            "language" => $new_account["language"]
        ];
        $this->scribeRepository->Log("signup", $msg);

        return $new_account;
    }

    private function insert_default_equipments(int $user_id)
    {
        for ($i = 4; $i < 10; $i++) {
            $equipment = new Equipment();
            $equipment->item_type_id = $i;
            $equipment->item_id = 1;
            if ($i == 6) $equipment->item_count = 10; //미끼
            else $equipment->item_count = 1;
            if ($i == 4) $equipment->durability = 40; //낚싯대
            else if ($i == 7) $equipment->durability = 8; //릴
            else $equipment->durability = 0;
            $equipment->is_equipped = 1;
            $this->inventoryRepository->insert_equipment($user_id, $equipment);
        }
    }
}
