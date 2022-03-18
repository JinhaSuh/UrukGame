<?php

namespace service;

use dto\Equipment;
use dto\User;
use dto\Boat;
use exception\InvalidRequestBody;
use exception\UnknownHiveID;
use exception\UserException;
use repository\AccountRepository;
use repository\BoatRepository;
use repository\InventoryRepository;
use repository\UserRepository;

require_once __DIR__ . '/../repository/AccountRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/BoatRepository.php';
require_once __DIR__ . '/../repository/InventoryRepository.php';
require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../dto/Boat.php';
require_once __DIR__ . '/../dto/Equipment.php';

class AccountService
{
    private AccountRepository $accountRepository;
    private UserRepository $userRepository;
    private BoatRepository $boatRepository;
    private InventoryRepository $inventoryRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->userRepository = new UserRepository();
        $this->boatRepository = new BoatRepository();
        $this->inventoryRepository = new InventoryRepository();
    }

    public function select_accounts()
    {
        return $this->accountRepository->select_account_list();
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

        return $this->accountRepository->select_account($account);
    }

    /**
     * @throws InvalidRequestBody|UnknownHiveID|UserException
     */
    public function insert_account($account)
    {
        //필수 입력값을 입력받았는지 확인
        if (!isset($account["player_id"]) || !isset($account["password"])) {
            throw new InvalidRequestBody();
        }

        //계정 생성
        $new_account = $this->accountRepository->insert_account($account);

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
