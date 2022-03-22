<?php

namespace App\service;

use App\exception\UnknownUser;
use DateTime;
use App\dto\Boat;
use App\exception\BoatDurabilityShortage;
use App\exception\EquipmentNotReady;
use App\exception\FatigueShortage;
use App\exception\ForcedArrival;
use App\exception\FuelShortage;
use App\exception\GoldShortage;
use App\exception\InvalidError;
use App\exception\InvalidFishingState;
use App\exception\InvalidUserState;
use App\exception\LevelShortage;
use App\exception\MaxGrade;
use App\exception\PearlShortage;
use App\exception\Success;
use App\exception\UpgradeFailure;
use App\exception\UserException;
use App\exception\InvalidRequestBody;
use App\repository\BoatRepository;
use App\repository\InventoryRepository;
use App\repository\MapRepository;
use App\repository\UserRepository;

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
     * @throws UserException|InvalidRequestBody|UnknownUser
     */
    public function select_boat($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->boatRepository->select_boat($user["user_id"]);
    }

    /**
     * @throws InvalidRequestBody|UserException|UpgradeFailure|InvalidError|GoldShortage|PearlShortage|MaxGrade|InvalidUserState|UnknownUser
     */
    public function upgrade_boat($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        //플레이어 상태 확인
        $user_state = $this->userRepository->select_user_state($user["user_id"]);
        if ($user_state["state"] != 0) throw new InvalidUserState();

        //일단 내 보트 상태 가져오기
        $result = $this->boatRepository->select_boat($user["user_id"]);
        $curr_boat = new Boat();
        $curr_boat->set_boat_id($result["boat_id"]);
        $curr_boat->set_durability($result["durability"]);
        $curr_boat->set_fuel($result["fuel"]);
        $curr_boat->set_departure_time(new DateTime($result["departure_time"]));
        $curr_boat->set_map_id($result["map_id"]);

        //다음 등급 정보와 업그레이드에 필요한 재화 및 확률 가져오기
        $boat_upgrade_data = $this->boatRepository->select_boat_upgrade_data($curr_boat->boat_id);
        //이미 최대 등급인 경우
        if (empty($boat_upgrade_data)) throw new MaxGrade();

        //재화 소비
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

        //성공 여부에 따라 업그레이드 또는 내구도 감소
        $success_rate = $boat_upgrade_data["success_rate"];
        $ran_num = rand(1, 100);
        if ($ran_num <= $success_rate) {
            $next_step_boat_data = $this->boatRepository->select_boat_data($curr_boat->boat_id + 1);
            $curr_boat->boat_id += 1;
            $curr_boat->durability = $next_step_boat_data["max_durability"];
            $curr_boat->fuel = $next_step_boat_data["max_fuel"];
            return $this->boatRepository->update_boat($user["user_id"], $curr_boat);
        } else {
            if ($curr_boat->durability - $boat_upgrade_data["reduce_durability_case_failure"] < 0) $curr_boat->durability = 0;
            else $curr_boat->durability -= $boat_upgrade_data["reduce_durability_case_failure"];
            return $this->boatRepository->update_boat($user["user_id"], $curr_boat);
            throw new UpgradeFailure();
        }
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
        if ($boat->get_map_id() != 0) throw new InvalidError();
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
        if ($map_list[$input["map_id"] - 1]["level_limit"] > $user["level"]) throw new LevelShortage();
        //출항 비용
        if ($user["gold"] < $map_list[$input["map_id"] - 1]["departure_cost"]) throw new GoldShortage();
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
            $boat->fuel -= 1;
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

        return new Success();
    }

    /**
     * @throws InvalidRequestBody|InvalidError|UnknownUser|InvalidUserState
     */
    public function arrival($input)
    {
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        //입항 가능 여부 판별
        //플레이어 상태 확인
        $user_state = $this->userRepository->select_user_state($input["user_id"]);
        if ($user_state["state"] != 2) throw new InvalidUserState();
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

        return new Success();
    }

}
