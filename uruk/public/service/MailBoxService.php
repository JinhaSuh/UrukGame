<?php

namespace service;

use dto\Equipment;
use exception\ExpiredMail;
use exception\InvalidError;
use exception\InvalidRequestBody;
use exception\UnknownMail;
use exception\UserException;
use repository\BoatRepository;
use repository\InventoryRepository;
use repository\MailBoxRepository;
use repository\UserRepository;

require_once __DIR__ . '/../repository/MailBoxRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/InventoryRepository.php';
require_once __DIR__ . '/../repository/BoatRepository.php';
require_once __DIR__ . '/../exception/ExpiredMail.php';

class MailBoxService
{
    private MailBoxRepository $mailBoxRepository;
    private UserRepository $userRepository;
    private InventoryRepository $inventoryRepository;
    private BoatRepository $boatRepository;

    public function __construct()
    {
        $this->mailBoxRepository = new MailBoxRepository();
        $this->userRepository = new UserRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->boatRepository = new BoatRepository();
    }

    /**
     * @throws InvalidRequestBody|UserException
     */
    public function select_mailBox($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        $now_date = date("Y-m-d H:i:s");
        return $this->mailBoxRepository->select_mailBox($user["user_id"], $now_date);
    }

    /**
     * @throws InvalidRequestBody|UnknownMail|ExpiredMail|UserException|InvalidError
     */
    public function receive_item($data)
    {
        if (!isset($data["user_id"]) || !isset($data["mail_id"])) {
            throw new InvalidRequestBody();
        }

        $now_date = date("Y-m-d H:i:s");
        $result = $this->mailBoxRepository->select_mailBox_item($data["user_id"], $data["mail_id"], $now_date);

        if (strtotime($now_date) > strtotime($result["expr_date"])) throw new ExpiredMail();

        switch ($result["item_type_id"]) {
            case 1: //gold
            case 2: //pearl
                $now_user = $this->userRepository->select_user($data);
                if ($result["item_type_id"] == 1) $now_user["gold"] += $result["item_count"];
                else $now_user["pearl"] += $result["item_count"];
                $updated_user = $this->userRepository->update_user($now_user);
                break;
            case 3: //fish
                //TODO : INSERT INTO water_tank VALUES fish
                break;
            case 4: //fishing_rod
            case 5: //weight
            case 6: //bait
            case 7: //reel
            case 8: //hook
            case 9: //fishing_line
                $equipment = new Equipment();
                $equipment->item_type_id = $result["item_type_id"];
                $equipment->item_id = $result["item_id"];
                $equipment->item_count = $result["item_count"];
                if ($result["item_type_id"] == 4 || $result["item_type_id"] == 7) {
                    //해당 채비의 최대 내구도 구해서 세팅해주기
                    $equipment_info = $this->inventoryRepository->select_equip_info($equipment);
                    $equipment->durability = $equipment_info["max_durability"];
                } else $equipment->durability = 0;
                $equipment->is_equipped = 0;
                $updated_inventory = $this->inventoryRepository->insert_equipment($data["user_id"], $equipment);
                break;
            case 10: //boat
                //TODO : UPDATE boat
                break;
        }

        return $this->mailBoxRepository->delete_mailBox_item($data["user_id"], $data["mail_id"]);
    }
}
