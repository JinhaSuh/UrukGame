<?php

namespace App\service;

use DateTime;
use App\dto\Equipment;
use App\dto\Fish;
use App\dto\FishInfo;
use App\exception\BoatDurabilityShortage;
use App\exception\EquipmentNotReady;
use App\exception\FatigueShortage;
use App\exception\FuelShortage;
use App\exception\HookingFailed;
use App\exception\InvalidError;
use App\exception\InvalidFishingState;
use App\exception\InvalidRequestBody;
use App\exception\InvalidWaterDepth;
use App\exception\PrepDurabilityShortage;
use App\exception\Success;
use App\exception\SuppressFailed;
use App\exception\UnknownFish;
use App\exception\UnknownItemType;
use App\exception\UnknownMap;
use App\exception\UnknownUser;
use App\repository\BoatRepository;
use App\repository\CollectionRepository;
use App\repository\FishingRepository;
use App\repository\InventoryRepository;
use App\repository\MapRepository;
use App\repository\UserRepository;
use App\repository\WaterTankRepository;
use App\repository\AuctionRepository;

class FishingService
{
    private FishingRepository $fishingRepository;
    private UserRepository $userRepository;
    private InventoryRepository $inventoryRepository;
    private BoatRepository $boatRepository;
    private MapRepository $mapRepository;
    private WaterTankRepository $waterTankRepository;
    private CollectionRepository $collectionRepository;
    private AuctionRepository $auctionRepository;

    public function __construct()
    {
        $this->fishingRepository = new FishingRepository();
        $this->userRepository = new UserRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->boatRepository = new BoatRepository();
        $this->mapRepository = new MapRepository();
        $this->waterTankRepository = new WaterTankRepository();
        $this->collectionRepository = new CollectionRepository();
        $this->auctionRepository = new AuctionRepository();
    }

    /**
     * @throws InvalidRequestBody|UnknownUser|FatigueShortage|EquipmentNotReady|BoatDurabilityShortage|InvalidFishingState|FuelShortage|InvalidWaterDepth|InvalidError|PrepDurabilityShortage
     */
    public function start_fishing($input)
    {
        if (!isset($input["user_id"]) || !isset($input["depth"])) {
            throw new InvalidRequestBody();
        }

        //???????????? ?????? ?????? ??? ?????? ?????? ?????? ??????
        $user_state = $this->userRepository->select_user_state($input["user_id"]);
        if ($user_state["state"] != 2) throw new InvalidFishingState();

        //?????????, ?????? ?????? ??????
        $user = $this->userRepository->select_user($input);
        if ($user["fatigue"] <= 0) throw new FatigueShortage();

        //?????? ??????
        $equipSlot = $this->inventoryRepository->select_equipSlot($input["user_id"]);
        if (count($equipSlot) != 6) throw new EquipmentNotReady();
        if ($equipSlot[0]["durability"] <= 0 || $equipSlot[3]["durability"] <= 0) { //?????????
            throw new PrepDurabilityShortage();
        }

        //??? ?????????, ?????? ??????
        $boat = $this->boatRepository->select_boat($input["user_id"]);
        if ($boat["map_id"] == 0) throw new InvalidFishingState();
        if ($boat["durability"] <= 0) throw new BoatDurabilityShortage();
        if ($boat["fuel"] <= 0) throw new FuelShortage();

        //?????? ??????
        $map_list = $this->mapRepository->select_map_list();
        $curr_map_max_depth = $map_list[$boat["map_id"]]["max_water_depth"];
        if ($input["depth"] > $curr_map_max_depth) throw new InvalidWaterDepth();

        //???????????? ?????? ??????????????? ??????
        $this->userRepository->update_user_state($input["user_id"], 3, $input["depth"]);

        return new Success();
    }

    /**
     * @throws InvalidRequestBody|UnknownUser|InvalidWaterDepth|InvalidFishingState|UnknownMap|UnknownFish|UnknownItemType|InvalidError|HookingFailed|SuppressFailed
     */
    public function end_fishing($input)
    {
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        //???????????? ?????? ?????? ??? ?????? ?????? ?????? ??????
        $user_state = $this->userRepository->select_user_state($input["user_id"]);
        if ($user_state["state"] != 3) throw new InvalidFishingState();

        //????????? ?????? ?????? ????????????
        $equipSlot = $this->inventoryRepository->select_equipSlot($input["user_id"]);

        //?????????, ????????? - ?????? ??????
        $fishing_line = Equipment::Deserialize($equipSlot[5]);
        $fishing_line_data = $this->inventoryRepository->select_equip_data($fishing_line);
        $fishing_rod = Equipment::Deserialize($equipSlot[0]);
        $fishing_rod_data = $this->inventoryRepository->select_equip_data($fishing_rod);
        $hooking_percent_average = ($fishing_line_data["buff_hooking_rate"] + $fishing_rod_data["buff_hooking_rate"]) / 2;
/*        if (rand(1, 100) > $hooking_percent_average) {
            $this->endFishing($input, $equipSlot);
            throw new HookingFailed();
        }*/

        //???, ?????????, ????????? - ?????? ??????
        $hook = Equipment::Deserialize($equipSlot[4]);
        $hook_data = $this->inventoryRepository->select_equip_data($hook);
        $suppress_percent_average = ($hook_data["buff_suppress_rate"] + $fishing_line_data["buff_suppress_rate"] + $fishing_rod_data["buff_suppress_rate"]) / 3;
/*        if (rand(1, 100) > $suppress_percent_average) {
            $this->endFishing($input, $equipSlot);
            throw new SuppressFailed();
        }*/

        //?????? ??? ?????? ?????? ????????? ???????????? ???????????? ?????? ?????? ?????????
        $boat = $this->boatRepository->select_boat($input["user_id"]);
        $map_drop_fish_list = $this->fishingRepository->select_map_fish_drop_data($boat["map_id"], $user_state["depth"]);
        $map_drop_item_list = $this->fishingRepository->select_map_item_drop_data($boat["map_id"]);

        //?????? ??????
        $user = $this->userRepository->select_user($input);

        //??? - ????????? ?????? ??????
        if (rand(1, 100) > $hook_data["buff_fish_drop_rate"]) { //?????? ??????
            $equipment = $map_drop_item_list[rand(0, count($map_drop_item_list) - 1)];

            //??????????????? ??????
            $caught_equipment = Equipment::Deserialize($equipment);

            //?????? ????????? ?????? ????????? ????????? ???????????????
            $equipment_data = $this->inventoryRepository->select_equip_data($caught_equipment);
            if ($caught_equipment->item_type_id == 4 || $caught_equipment->item_type_id == 7) { //?????????, ???
                $caught_equipment->durability = $equipment_data["max_durability"];
            } else $caught_equipment->durability = 0;
            $caught_equipment->is_equipped = 0;
            $updated_inventory = $this->inventoryRepository->insert_equipment($input["user_id"], $caught_equipment);
            $fishing_result = $caught_equipment;

            $user["exp"] += 5;

        } else { //??????
            $fish_list = array();
            for ($i = 0; $i < count($map_drop_fish_list); $i++) {
                $fish_data = $this->fishingRepository->select_fish_data($map_drop_fish_list[$i]["fish_id"]);
                array_push($fish_list, $fish_data);
            }

            //?????? ?????????
            $caught_fish = $fish_list[rand(0, count($fish_list) - 1)];
            $fish = Fish::Deserialize($caught_fish);
            $fish_info = new FishInfo();
            $fish_info->user_id = $input["user_id"];
            $fish_info->fish_id = $fish->fish_id;
            $fish_info->length = rand($fish->min_length, $fish->max_length);
            $fish_info->caught_time = new DateTime(date("Y-m-d H:i:s"));

            //????????? ??????
            $updated_water_tank = $this->waterTankRepository->insert_water_tank_fish($input["user_id"], $fish_info);

            //????????? ????????? ??????
            $fish_result = $this->collectionRepository->select_collection_fish($input["user_id"], $fish_info->fish_id);
            /*if (empty($fish_result)) {
                $this->collectionRepository->insert_collection_fish($input["user_id"], $fish_info->fish_id);
                $user_collection = $this->collectionRepository->select_collection($input["user_id"]);

                //?????? ?????? ??????
                $collection_reward_data = $this->collectionRepository->select_collection_reward_data();
                for ($i = 0; $i < count($collection_reward_data); $i++) {
                    if (count($user_collection) == $collection_reward_data[$i]["filled_count"]) {
                        if ($collection_reward_data[$i]["reward_item_type_id"] == 1)
                            $user["gold"] += $collection_reward_data[$i]["reward_item_count"];
                        else
                            $user["pearl"] += $collection_reward_data[$i]["reward_item_count"];
                        break;
                    }

                }
            }*/

            $user["exp"] += $fish->exp;

            $fishing_result = [
                'fish_id' => $fish_info->fish_id,
                'fish_name' => $fish->fish_name,
                'length' => $fish_info->length,
                'caught_time' => date("Y-m-d H:i:s", $fish_info->caught_time->getTimestamp())
            ];
        }

        //?????? ?????? ????????????
        $updated_user = $this->userRepository->update_user($user);

        $this->endFishing($input, $equipSlot);

        //???????????? ?????? ??? return
        return $fishing_result;
    }

    public function endFishing($input, $equipSlot)
    {
        //???????????? ?????? ??????????????? ??????
        $this->userRepository->update_user_state($input["user_id"], 2, 0);

        //?????? ??????
        $updated_bait = Equipment::Deserialize($equipSlot[2]);
        $updated_bait->item_count -= 1;
        if ($updated_bait->item_count < 0) $updated_bait->is_equipped = 0;
        $updated_bait_result = $this->inventoryRepository->update_equipment($input["user_id"], $updated_bait->inv_id, $updated_bait);

        //????????? ????????? ??????
        $updated_fishing_rod = Equipment::Deserialize($equipSlot[0]);
        $updated_fishing_rod->durability -= 5;
        if ($updated_fishing_rod->durability <= 0) {
            $updated_fishing_rod->durability = 0;
            $updated_fishing_rod->is_equipped = 0;
        }
        $updated_fishing_rod_result = $this->inventoryRepository->update_equipment($input["user_id"], $updated_fishing_rod->inv_id, $updated_fishing_rod);        //????????? ????????? ??????

        //??? ????????? ??????
        $updated_fishing_reel = Equipment::Deserialize($equipSlot[3]);
        $updated_fishing_reel->durability -= 1;
        if ($updated_fishing_reel->durability <= 0) {
            $updated_fishing_reel->durability = 0;
            $updated_fishing_reel->is_equipped = 0;
        }
        $updated_fishing_rod_result = $this->inventoryRepository->update_equipment($input["user_id"], $updated_fishing_reel->inv_id, $updated_fishing_reel);
    }
}
