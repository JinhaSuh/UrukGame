<?php

namespace App\service;

use App\dto\Equipment;
use App\exception\ExpiredMail;
use App\exception\InvalidError;
use App\exception\InvalidRequestBody;
use App\exception\UnknownItemType;
use App\exception\UnknownMail;
use App\exception\UnknownUser;
use App\repository\BoatRepository;
use App\repository\FishingRepository;
use App\repository\InventoryRepository;
use App\repository\MailBoxRepository;
use App\repository\UserRepository;

class MailBoxService
{
    private MailBoxRepository $mailBoxRepository;
    private UserRepository $userRepository;
    private InventoryRepository $inventoryRepository;
    private BoatRepository $boatRepository;
    private FishingRepository $fishingRepository;

    public function __construct()
    {
        $this->mailBoxRepository = new MailBoxRepository();
        $this->userRepository = new UserRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->fishingRepository = new FishingRepository();
    }

    /**
     * @throws InvalidRequestBody|UnknownUser
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
     * @throws InvalidRequestBody|UnknownMail|ExpiredMail|UnknownUser|InvalidError|UnknownItemType
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
                //TODO(Later) : 물고기도 선물을 받나
                //INSERT INTO water_tank VALUES fish
//                $fish_data = $this->fishingRepository->select_fish_data($result["item_id"]);
//                $updated_water_tank = $this->waterTankRepository->insert_water_tank_fish($data["user_id"], $fish_info);
//
//                $fish = Fish::Deserialize($caught_fish);
//                $fish_info = new FishInfo();
//                $fish_info->user_id = $input["user_id"];
//                $fish_info->fish_id = $fish->fish_id;
//                $fish_info->length = rand($fish->min_length, $fish->max_length);
//                $fish_info->caught_time = new DateTime(date("Y-m-d H:i:s"));
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
                    $equipment_info = $this->inventoryRepository->select_equip_data($equipment);
                    $equipment->durability = $equipment_info["max_durability"];
                } else $equipment->durability = 0;
                $equipment->is_equipped = 0;
                $updated_inventory = $this->inventoryRepository->insert_equipment($data["user_id"], $equipment);
                break;
            case 10: //boat
                //TODO(Later) : 배도 선물을 받나
                //UPDATE boat
                break;
        }

        return $this->mailBoxRepository->delete_mailBox_item($data["user_id"], $data["mail_id"]);
    }
}
