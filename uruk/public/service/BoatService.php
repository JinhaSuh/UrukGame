<?php

namespace service;

use DateTime;
use dto\Boat;
use exception\BoatDurabilityShortage;
use exception\EquipmentNotReady;
use exception\FatigueShortage;
use exception\ForcedArrival;
use exception\FuelShortage;
use exception\GoldShortage;
use exception\InvalidError;
use exception\InvalidFishingState;
use exception\LevelShortage;
use exception\MaxGrade;
use exception\PearlShortage;
use exception\UpgradeFailure;
use exception\UserException;
use exception\InvalidRequestBody;
use repository\BoatRepository;
use repository\InventoryRepository;
use repository\MapRepository;
use repository\UserRepository;

require_once __DIR__ . '/../repository/BoatRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/InventoryRepository.php';
require_once __DIR__ . '/../repository/MapRepository.php';
require_once __DIR__ . '/../exception/MaxGrade.php';
require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../exception/InvalidRequestBody.php';
require_once __DIR__ . '/../exception/InvalidError.php';
require_once __DIR__ . '/../exception/UpgradeFailure.php';
require_once __DIR__ . '/../exception/GoldShortage.php';
require_once __DIR__ . '/../exception/PearlShortage.php';
require_once __DIR__ . '/../exception/EquipmentNotReady.php';
require_once __DIR__ . '/../exception/BoatDurabilityShortage.php';
require_once __DIR__ . '/../exception/InvalidFishingState.php';
require_once __DIR__ . '/../exception/FatigueShortage.php';
require_once __DIR__ . '/../exception/LevelShortage.php';
require_once __DIR__ . '/../exception/FuelShortage.php';
require_once __DIR__ . '/../exception/InvalidFishingState.php';
require_once __DIR__ . '/../exception/ForcedArrival.php';
require_once __DIR__ . '/../dto/Boat.php';

class BoatService
{
    private BoatRepository $boatRepository;
    private UserRepository $userRepository;
    private InventoryRepository $inventoryRepository;
    private MapRepository $mapRepository;

    public function __construct()
    {
        $this->boatRepository = new BoatRepository();
        $this->userRepository = new UserRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->mapRepository = new MapRepository();
    }

    /**
     * @throws UserException|InvalidRequestBody
     */
    public function select_boat($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->boatRepository->select_boat($user["user_id"]);
    }

    /**
     * @throws InvalidRequestBody|UserException|UpgradeFailure|InvalidError|GoldShortage|PearlShortage|MaxGrade
     */
    public function upgrade_boat($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        //일단 내 보트 상태 가져오기
        $result = $this->boatRepository->select_boat($user["user_id"]);
        $curr_boat = new Boat();
        $curr_boat->set_boat_id($result["boat_id"]);
        $curr_boat->set_durability($result["durability"]);
        $curr_boat->set_fuel($result["fuel"]);
        $curr_boat->set_departure_time(new DateTime($result["departure_time"]));
        $curr_boat->set_map_id($result["map_id"]);

        //다음 등급 정보와 업그레이드에 필요한 재화 및 확률 가져오기
        $boat_upgrade_data = $this->boatRepository->select_boat_upgrade_data($curr_boat->boat_id + 1);
        //이미 최대 등급인 경우
        if (empty($boat_upgrade_data)) throw new MaxGrade();

        $curr_user = $this->userRepository->select_user($user);
        switch ($boat_upgrade_data["need_item_type_id"]) {
            case 1:
                $curr_user["gold"] -= $boat_upgrade_data["need_count"];
                if ($curr_user["gold"] < 0) throw new GoldShortage();
                break;
            case 2:
                $curr_user["pearl"] -= $boat_upgrade_data["need_count"];
                if ($curr_user["pearl"] < 0) throw new PearlShortage();
                break;
        }

        $updated_user = $this->userRepository->update_user($curr_user);

        $success_rate = $boat_upgrade_data["success_rate"];
        $ran_num = rand(1, 100);
        if ($ran_num <= $success_rate) {
            $curr_boat->boat_id += 1;
            return $this->boatRepository->update_boat($user["user_id"], $curr_boat);
        } else
            throw new UpgradeFailure();
    }

    /**
     * @throws InvalidRequestBody|UserException|BoatDurabilityShortage|EquipmentNotReady|FatigueShortage|LevelShortage|GoldShortage|InvalidError|FuelShortage|MaxGrade|InvalidFishingState|ForcedArrival
     */
    public function departure($input)
    {
        if (!isset($input["user_id"]) || !isset($input["map_id"])) {
            throw new InvalidRequestBody();
        }

        //출항 가능 여부 판별
        //배 내구도 & 연료
        $result = $this->boatRepository->select_boat($input["user_id"]);
        $boat = new Boat();
        $boat->set_boat_id($result["boat_id"]);
        $boat->set_durability($result["durability"]);
        $boat->set_fuel($result["fuel"]);
        $boat->set_departure_time(new DateTime($result["departure_time"]));
        $boat->set_map_id($result["map_id"]);
        if($boat->get_map_id()!=0) throw new InvalidError();
        $boat_info = $this->boatRepository->select_boat_data($boat->get_boat_id());
        if ($boat->durability <= 0) throw new BoatDurabilityShortage();
        if ($boat->fuel <= 0) throw new FuelShortage();
        //채비 완료
        $equipSlot = $this->inventoryRepository->select_equipSlot($input["user_id"]);
        if (count($equipSlot) != 6) throw new EquipmentNotReady();
        //플레이어 상태 확인
        $user_state = $this->userRepository->select_user_state($input["user_id"]);
        if ($user_state["state"] != 0) throw new InvalidFishingState();
        //입장 레벨
        $map_list = $this->mapRepository->select_map_list();
        $user = $this->userRepository->select_user($input);
        if ($map_list[$input["map_id"]]["level_limit"] > $user["level"]) throw new LevelShortage();
        //출항 비용
        if ($user["gold"] < $map_list[$input["map_id"]]["departure_cost"]) throw new GoldShortage();
        //피로도
        if ($user["fatigue"] - 4 <= 0) throw new FatigueShortage();

        //출항 시작
        //피로도, 출항비용 차감
        $user["fatigue"] -= 4;
        $user["gold"] -= $map_list[$input["map_id"] - 1]["departure_cost"];
        $updated_user = $this->userRepository->update_user($user);
        //플레이어 상태 항해중으로 변경
        //$this->userRepository->update_user_state($input["user_id"], 1, 0);
        //시간이 흐르면서 내구도나 연료가 0이하면 강제 입항
        for ($i = 0; $i < $map_list[$input["map_id"] - 1]["departure_time"]; $i++) {
            $boat->durability -= $map_list[$input["map_id"] - 1]["reduce_durability_per_meter"] * $boat_info["reduce_durability_per_min"];
            $boat->fuel -= map_list[$input["map_id"]]["reduce_durability_per_meter"];
            //출항 중에 내구도 0 이하로 강제 입항
            if ($boat->durability <= 0) {
                $boat->durability = 0;
                if ($boat->fuel <= 0) $boat->fuel = 0;
                $updated_boat = $this->boatRepository->update_boat($input["user_id"], $boat);

                //강제입항
                $this->userRepository->update_user_state($input["user_id"], 2, 0);
                $this->arrival($input);
                throw new ForcedArrival();
            } else if ($boat->fuel <= 0) {
                $boat->fuel = 0;
                $updated_boat = $this->boatRepository->update_boat($input["user_id"], $boat);

                //강제입항
                $this->userRepository->update_user_state($input["user_id"], 2, 0);
                $this->arrival($input);
                throw new ForcedArrival();
            } else {
                $updated_boat = $this->boatRepository->update_boat($input["user_id"], $boat);
            }
        }

        //목적지에 도착
        //배 위치 변경
        $now_date = date("Y-m-d H:i:s");
        $boat->set_departure_time(new DateTime($now_date));
        $boat->set_map_id($input["map_id"]);
        $updated_boat = $this->boatRepository->update_boat($input["user_id"], $boat);
        //플레이어 상태 항해중으로 변경
        $this->userRepository->update_user_state($input["user_id"], 2, 0);
    }

    /**
     * @throws InvalidRequestBody|UserException|InvalidError
     */
    public function arrival($input)
    {
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        //입항 가능 여부 판별
        //플레이어 상태 확인
        $user_state = $this->userRepository->select_user_state($input["user_id"]);
        if ($user_state["state"] != 2) throw new InvalidError();
        $this->userRepository->update_user_state($input["user_id"], 0, 0);

        //배 위치 변경
        $result = $this->boatRepository->select_boat($input["user_id"]);
        $boat = new Boat();
        $boat->set_boat_id($result["boat_id"]);
        $boat->set_durability($result["durability"]);
        $boat->set_fuel($result["fuel"]);
        $boat->set_departure_time(new DateTime($result["departure_time"]));
        $boat->set_map_id($result["map_id"]);
        $boat->set_map_id(0);
        $updated_boat = $this->boatRepository->update_boat($input["user_id"], $boat);
    }

}
