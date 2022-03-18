<?php

namespace service;

use DateTime;
use dto\Equipment;
use dto\Fish;
use dto\FishInfo;
use exception\BoatDurabilityShortage;
use exception\EquipmentNotReady;
use exception\FatigueShortage;
use exception\FuelShortage;
use exception\HookingFailed;
use exception\InvalidError;
use exception\InvalidFishingState;
use exception\InvalidRequestBody;
use exception\InvalidWaterDepth;
use exception\PrepDurabilityShortage;
use exception\SuppressFailed;
use exception\UnknownFish;
use exception\UnknownItemType;
use exception\UnknownMap;
use exception\UserException;
use repository\BoatRepository;
use repository\FishingRepository;
use repository\InventoryRepository;
use repository\MapRepository;
use repository\UserRepository;
use repository\WaterTankRepository;

require_once __DIR__ . '/../repository/FishingRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/InventoryRepository.php';
require_once __DIR__ . '/../repository/BoatRepository.php';
require_once __DIR__ . '/../repository/MapRepository.php';
require_once __DIR__ . '/../repository/WaterTankRepository.php';
require_once __DIR__ . '/../exception/InvalidRequestBody.php';
require_once __DIR__ . '/../exception/FatigueShortage.php';
require_once __DIR__ . '/../exception/EquipmentNotReady.php';
require_once __DIR__ . '/../exception/BoatDurabilityShortage.php';
require_once __DIR__ . '/../exception/InvalidFishingState.php';
require_once __DIR__ . '/../exception/FuelShortage.php';
require_once __DIR__ . '/../exception/InvalidWaterDepth.php';
require_once __DIR__ . '/../exception/PrepDurabilityShortage.php';
require_once __DIR__ . '/../exception/HookingFailed.php';
require_once __DIR__ . '/../exception/SuppressFailed.php';
require_once __DIR__ . '/../dto/Fish.php';
require_once __DIR__ . '/../dto/Equipment.php';
require_once __DIR__ . '/../dto/FishInfo.php';

class FishingService
{
    private FishingRepository $fishingRepository;
    private UserRepository $userRepository;
    private InventoryRepository $inventoryRepository;
    private BoatRepository $boatRepository;
    private MapRepository $mapRepository;
    private WaterTankRepository $waterTankRepository;

    public function __construct()
    {
        $this->fishingRepository = new FishingRepository();
        $this->userRepository = new UserRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->boatRepository = new BoatRepository();
        $this->mapRepository = new MapRepository();
        $this->waterTankRepository = new WaterTankRepository();
    }

    /**
     * @throws InvalidRequestBody|UserException|FatigueShortage|EquipmentNotReady|BoatDurabilityShortage|InvalidFishingState|FuelShortage|InvalidWaterDepth|InvalidError|PrepDurabilityShortage
     */
    public function start_fishing($input)
    {
        if (!isset($input["user_id"]) || !isset($input["depth"])) {
            throw new InvalidRequestBody();
        }

        //피로도, 현재 위치 검사
        $user = $this->userRepository->select_user($input);
        if ($user["fatigue"] <= 0) throw new FatigueShortage();

        //채비 검사
        $equipSlot = $this->inventoryRepository->select_equipSlot($input["user_id"]);
        if (count($equipSlot) != 6) throw new EquipmentNotReady();
        if ($equipSlot[0]["durability"] <= 0 || $equipSlot[3]["durability"] <= 0) { //낚싯대
            throw new PrepDurabilityShortage();
        }

        //배 내구도, 연료 검사
        $boat = $this->boatRepository->select_boat($input["user_id"]);
        if ($boat["map_id"] == 0) throw new InvalidFishingState();
        if ($boat["durability"] <= 0) throw new BoatDurabilityShortage();
        if ($boat["fuel"] <= 0) throw new FuelShortage();

        //수심 검사
        $map_list = $this->mapRepository->select_map_list();
        $curr_map_max_depth = $map_list[$boat["map_id"]]["max_water_depth"];
        if ($input["depth"] > $curr_map_max_depth) throw new InvalidWaterDepth();

        //플레이어 상태 낚시중으로 변경
        $this->userRepository->update_user_state($input["user_id"], 3, $input["depth"]);
    }

    /**
     * @throws InvalidRequestBody|UserException|InvalidWaterDepth|InvalidFishingState|UnknownMap|UnknownFish|UnknownItemType|InvalidError|HookingFailed|SuppressFailed
     */
    public function end_fishing($input)
    {
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        //플레이어 상태 확인 및 낚시 던진 깊이 검사
        $user_state = $this->userRepository->select_user_state($input["user_id"]);
        if ($user_state["state"] != 3) throw new InvalidFishingState();

        //장착한 채비 정보 가져오기
        $equipSlot = $this->inventoryRepository->select_equipSlot($input["user_id"]);
        $equipped_fishing_rod = $equipSlot[0];
        $equipped_weight = $equipSlot[1];
        $equipped_bait = $equipSlot[2];
        $equipped_fishing_reel = $equipSlot[3];
        $equipped_hook = $equipSlot[4];
        $equipped_fishing_line = $equipSlot[5];

        //낚싯줄, 낚싯대 - 훅킹 확률
        $hooking_percent_average = ($equipped_fishing_line["buff_hooking_rate"] + $equipped_fishing_rod["buff_hooking_rate"]) / 2;
        if (rand(1, 100) > $hooking_percent_average) throw new HookingFailed();

        //훅, 낚시줄, 낚싯대 - 제압 확률
        $suppress_percent_average = ($equipped_hook["buff_suppress_rate"] + $equipped_fishing_line["buff_suppress_rate"] + $equipped_fishing_rod["buff_suppress_rate"]) / 3;
        if (rand(1, 100) > $suppress_percent_average) throw new SuppressFailed();

        //현재 맵 채비 내린 수심에 등장하는 물고기 리스트
        $boat = $this->boatRepository->select_boat($input["user_id"]);
        $map_drop_fish_list = $this->fishingRepository->select_map_fish_drop_data($boat["map_id"], $user_state["depth"]);
        $map_drop_item_list = $this->fishingRepository->select_map_item_drop_data($boat["map_id"]);

        //훅 - 물고기 등장 확률
        $hook = Equipment::Deserialize($equipped_hook);
        $hook_data = $this->inventoryRepository->select_equip_info($hook);

        if (rand(1, 100) > $hook_data["buff_fish_drop_rate"]) { //부품 조각
            $equipment = $map_drop_item_list[rand(0, count($map_drop_item_list) - 1)];

            //인벤토리에 추가
            $caught_equipment = Equipment::Deserialize($equipment);

            if ($caught_equipment->item_type_id == 4 || $caught_equipment->item_type_id == 7) {
                //해당 채비의 최대 내구도 구해서 세팅해주기
                $equipment_info = $this->inventoryRepository->select_equip_info($equipment);
                $caught_equipment->durability = $equipment_info["max_durability"];
            } else $caught_equipment->durability = 0;

            $updated_inventory = $this->inventoryRepository->insert_equipment($input["user_id"], $caught_equipment);
            $fishing_result = $caught_equipment;
        } else { //낚시
            //TODO(Later) : 날씨 영향, 희귀 물고기 등장 확률
            $fish_list = array();
            for ($i = 0; $i < count($map_drop_fish_list); $i++) {
                $fish_data = $this->fishingRepository->select_fish_data($map_drop_fish_list[$i]["fish_id"]);
                array_push($fish_list, $fish_data);
            }

            //잡은 물고기
            $caught_fish = $fish_list[rand(0, count($fish_list))];
            $fish = Fish::Deserialize($caught_fish);
            $fish_info = new FishInfo();
            $fish_info->user_id = $input["user_id"];
            $fish_info->fish_id = $fish->fish_id;
            $fish_info->length = rand($fish->min_length, $fish->max_length);
            $fish_info->caught_time = new DateTime(date("Y-m-d H:i:s"));

            //수조에 넣기
            $updated_water_tank = $this->waterTankRepository->insert_water_tank($input["user_id"], $fish_info);

            $fishing_result = [
                'fish_id' => $fish_info->fish_id,
                'fish_name' => $fish->fish_name,
                'length' => $fish_info->length,
                'caught_time' => date("Y-m-d H:i:s", $fish_info->caught_time->getTimestamp())
            ];
        }

        //경험치 증가
        $user = $this->userRepository->select_user($input);
        $user["exp"] += 5;
        $update_user = $this->userRepository->update_user($user);

        //플레이어 상태 항해중으로 변경
        $this->userRepository->update_user_state($input["user_id"], 2, 0);

        //미끼 소비
        $updated_bait = Equipment::Deserialize($equipped_bait);
        $updated_bait->item_count -= 1;
        if ($updated_bait->item_count <= 0) $updated_bait->is_equipped = 0;
        $updated_bait_result = $this->inventoryRepository->update_equipment($input["user_id"], $equipped_bait["inv_id"], $updated_bait);

        //낚싯대 내구도 소비
        $updated_fishing_rod = Equipment::Deserialize($equipped_fishing_rod);
        $updated_fishing_rod->durability -= 5;
        if ($updated_fishing_rod->durability <= 0) {
            $updated_fishing_rod->durability = 0;
            $updated_fishing_rod->is_equipped = 0;
        }
        $updated_fishing_rod_result = $this->inventoryRepository->update_equipment($input["user_id"], $equipped_fishing_rod["inv_id"], $updated_fishing_rod);        //낚싯대 내구도 소비

        //릴 내구도 소비
        $updated_fishing_reel = Equipment::Deserialize($equipped_fishing_reel);
        $updated_fishing_reel->durability -= 1;
        if ($updated_fishing_reel->durability <= 0) {
            $updated_fishing_reel->durability = 0;
            $updated_fishing_reel->is_equipped = 0;
        }
        $updated_fishing_rod_result = $this->inventoryRepository->update_equipment($input["user_id"], $equipped_fishing_reel["inv_id"], $updated_fishing_reel);

        //낚시해서 얻은 템 return
        return $fishing_result;
    }
}
