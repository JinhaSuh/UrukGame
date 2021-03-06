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
use App\repository\ScribeRepository;
use App\repository\UserRepository;

class MailBoxService
{
    private MailBoxRepository $mailBoxRepository;
    private UserRepository $userRepository;
    private InventoryRepository $inventoryRepository;
    private FishingRepository $fishingRepository;
    private ScribeRepository $scribeRepository;

    public function __construct()
    {
        $this->mailBoxRepository = new MailBoxRepository();
        $this->userRepository = new UserRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->fishingRepository = new FishingRepository();
        $this->scribeRepository = new ScribeRepository();
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

                //scribe - asset
                $this->scribeRepository->AssetLog($updated_user, $result["item_type_id"], $result["item_count"], "receive_item");
                break;
            case 3: //fish
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
                    //?????? ????????? ?????? ????????? ????????? ???????????????
                    $equipment_info = $this->inventoryRepository->select_equip_data($equipment);
                    $equipment->durability = $equipment_info["max_durability"];
                } else $equipment->durability = 0;
                $equipment->is_equipped = 0;
                $updated_inventory = $this->inventoryRepository->insert_equipment($data["user_id"], $equipment);
                break;
            case 10: //boat
                break;
        }

        return $this->mailBoxRepository->delete_mailBox_item($data["user_id"], $data["mail_id"]);
    }
}
