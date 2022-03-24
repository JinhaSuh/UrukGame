<?php

namespace App\service;

use App\dto\Equipment;
use App\exception\InvalidError;
use App\exception\InvalidRequestBody;
use App\exception\UnknownItemType;
use App\exception\UnknownUser;
use App\repository\InventoryRepository;
use App\repository\ScribeRepository;
use App\repository\ShopRepository;
use App\repository\UserRepository;

class ShopService
{
    private ShopRepository $shopRepository;
    private InventoryRepository $inventoryRepository;
    private UserRepository $userRepository;
    private ScribeRepository $scribeRepository;

    public function __construct()
    {
        $this->shopRepository = new ShopRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->userRepository = new UserRepository();
        $this->scribeRepository = new ScribeRepository();
    }

    /**
     * @throws InvalidRequestBody
     */
    public function select_shop($input)
    {
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        $this->shopRepository->delete_user_shop($input["user_id"]);

        //임의의 아이템들 상점에 추가
        $shop_item_data_list = $this->shopRepository->select_shop_data();
        for ($i = 0; $i < 5; $i++) {
            $ran_idx = rand(0, count($shop_item_data_list) - 1);
            $this->shopRepository->insert_user_shop(
                $input["user_id"], $shop_item_data_list[$ran_idx]["item_type_id"],
                $shop_item_data_list[$ran_idx]["item_id"], $shop_item_data_list[$ran_idx]["item_count"],
                $shop_item_data_list[$ran_idx]["need_item_type_id"], $shop_item_data_list[$ran_idx]["need_count"]);
        }

        $shop_item_list = $this->shopRepository->select_shop($input["user_id"]);

        return $shop_item_list;
    }

    /**
     * @throws InvalidRequestBody|UnknownUser|InvalidError|UnknownItemType
     */
    public function buy_shop_item($input)
    {
        if (!isset($input["user_id"]) || !isset($input["shop_id"])) {
            throw new InvalidRequestBody();
        }

        $shop_item = $this->shopRepository->select_shop_item($input["user_id"], $input["shop_id"]);

        $equipment = new Equipment();
        $equipment->item_type_id = $shop_item["item_type_id"];
        $equipment->item_id = $shop_item["item_id"];
        $equipment->item_count = $shop_item["item_count"];

        //해당 채비의 최대 내구도 구해서 세팅해주기
        $equipment_data = $this->inventoryRepository->select_equip_data($equipment);
        if ($equipment->item_type_id == 4 || $equipment->item_type_id == 7) { //낚싯대, 릴
            $equipment->durability = $equipment_data["max_durability"];
        } else $equipment->durability = 0;
        $equipment->is_equipped = 0;
        $updated_inventory = $this->inventoryRepository->insert_equipment($input["user_id"], $equipment);

        //재화 소비
        $curr_user = $this->userRepository->select_user($input);
        if ($shop_item["need_item_type_id"] == 1) {
            $curr_user["gold"] -= $shop_item["need_count"];
        } else {
            $curr_user["pearl"] -= $shop_item["need_count"];
        }
        $updated_user = $this->userRepository->update_user($curr_user);

        //scribe - asset
        $this->scribeRepository->AssetLog($updated_user, $shop_item["need_item_type_id"], $shop_item["need_count"] * (-1), "buy_shop_item");

        return $updated_inventory;
    }

}
