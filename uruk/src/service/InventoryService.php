<?php

namespace App\service;

use App\dto\Equipment;
use App\exception\AlreadyEquippedType;
use App\exception\ExcessMaxDurability;
use App\exception\GoldShortage;
use App\exception\InvalidError;
use App\exception\InvalidRepairEquipment;
use App\exception\InvalidUpgradeEquipment;
use App\exception\InvalidRequestBody;
use App\exception\InvalidUserState;
use App\exception\MaxGrade;
use App\exception\PearlShortage;
use App\exception\SameEquippedState;
use App\exception\UnknownItemType;
use App\exception\UnknownUser;
use App\repository\InventoryRepository;
use App\repository\ScribeRepository;
use App\repository\UserRepository;

class InventoryService
{
    private InventoryRepository $inventoryRepository;
    private UserRepository $userRepository;
    private ScribeRepository $scribeRepository;

    public function __construct()
    {
        $this->inventoryRepository = new InventoryRepository();
        $this->userRepository = new UserRepository();
        $this->scribeRepository = new ScribeRepository();
    }

    /**
     * @throws InvalidRequestBody|UnknownUser
     */
    public function select_inventory($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->inventoryRepository->select_inventory($user["user_id"]);
    }

    /**
     * @throws UnknownUser|InvalidError|InvalidUpgradeEquipment|MaxGrade|Exception|UnknownItemType|InvalidUserState|InvalidRequestBody|UnknownItemType|GoldShortage|PearlShortage
     */
    public function upgrade_equipment($input)
    {
        if (!isset($input["user_id"]) || !isset($input["inv_id"])) {
            throw new InvalidRequestBody();
        }

        $user_id = $input["user_id"];
        $inv_id = $input["inv_id"];

        //플레이어 상태 확인
        $user_state = $this->userRepository->select_user_state($user_id);
        if ($user_state["state"] != 0) throw new InvalidUserState();

        //인벤토리의 해당 위치의 장비 가져오기
        $result = $this->inventoryRepository->select_inventory_single_item($user_id, $inv_id);
        $equipment = Equipment::Deserialize($result);

        //장비의 상세 info 가져오기
        if ($equipment->item_type_id == 4 || $equipment->item_type_id == 7 || $equipment->item_type_id == 9)
            $equipment_info = $this->inventoryRepository->select_equip_data($equipment);
        else //업그레이드 가능한 타입이 아닐 시
            throw new InvalidUpgradeEquipment();

        switch ($equipment->item_type_id) {
            case 4: // 낚싯대
                $cur_grade = $equipment_info["grade_id"];
                $cur_step = $equipment_info["step"];
                break;
            case 7: //릴
                $cur_grade = $equipment_info["grade_id"];
                $cur_step = $equipment_info["number"];
                break;
            case 9: //낚시줄
                $cur_grade = -1;
                $cur_step = $equipment_info["number"];
                break;
            default:
                throw new InvalidUpgradeEquipment();
        }

        //다음 단계의 장비 있는지 확인
        $next_step_equipment_info = $this->inventoryRepository->select_next_step_equip_data($equipment->item_type_id, $cur_grade, $cur_step + 1);
        //없을 경우
        if (empty($next_step_equipment_info)) throw new MaxGrade();

        //다음 단계로 업그레이드에 필요한 재화 기획 데이터 가져오기
        $equip_upgrade_data = $this->inventoryRepository->select_equip_upgrade_data($equipment->item_type_id, $equipment_info["grade_id"], $equipment_info["step"] + 1);
        $need_goods_type = $equip_upgrade_data["need_item_type_id"];
        $need_goods_count = $equip_upgrade_data["need_count"];

        //유저 정보 가져오기
        $input_user = array();
        $input_user["user_id"] = $user_id;
        $user = $this->userRepository->select_user($input_user);

        //재화 소비
        switch ($need_goods_type) {
            case 1:
                $user["gold"] -= $need_goods_count;
                if ($user->gold < 0) throw new GoldShortage();
                break;
            case 2:
                $user["pearl"] -= $need_goods_count;
                if ($user["pearl"] < 0) throw new PearlShortage();
                break;
            default:
                throw new InvalidError();
        }
        $updated_user = $this->userRepository->update_user($user);

        switch ($equipment->item_type_id) {
            case 4: // 낚싯대
                $equipment->item_id = $next_step_equipment_info["fishing_rod_id"];
                $equipment->durability = $next_step_equipment_info["max_durability"];
                break;
            case 7: //릴
                $equipment->item_id = $next_step_equipment_info["reel_id"];
                $equipment->durability = $next_step_equipment_info["max_durability"];
                break;
            case 9: //낚시줄
                $equipment->item_id = $next_step_equipment_info["fishing_line_id"];
                break;
            default:
                throw new InvalidUpgradeEquipment();
        }

        $upgraded_equipment = $this->inventoryRepository->update_equipment($user_id, $inv_id, $equipment);

        //scribe - asset
        $this->scribeRepository->AssetLog($updated_user, $equip_upgrade_data["need_item_type_id"], $equip_upgrade_data["need_count"] * (-1), "upgrade_equipment");

        return $upgraded_equipment;
    }

    /**
     * @throws SameEquippedState|AlreadyEquippedType|InvalidRequestBody|UnknownUser
     */
    public function equip_equipment($input)
    {
        if (!isset($input["user_id"]) || !isset($input["inv_id"])) {
            throw new InvalidRequestBody();
        }
        $user_id = $input["user_id"];
        $inv_id = $input["inv_id"];

        //인벤토리의 해당 위치의 장비 가져오기
        $result = $this->inventoryRepository->select_inventory_single_item($user_id, $inv_id);
        $equipment = Equipment::Deserialize($result);
        if ($equipment->is_equipped) throw new SameEquippedState();

        $equipSlot = $this->inventoryRepository->select_equipSlot($user_id);
        for ($i = 0; $i < count($equipSlot); $i++) {
            $item_type_id = $equipSlot[$i]["item_type_id"];
            if ($item_type_id == $equipment->item_type_id) throw new AlreadyEquippedType();
        }

        $equipment->is_equipped = 1;
        $updated_equipment = $this->inventoryRepository->update_equipment($user_id, $inv_id, $equipment);

        return $this->inventoryRepository->select_inventory($input["user_id"]);
    }

    /**
     * @throws SameEquippedState|InvalidRequestBody|UnknownUser
     */
    public function unequip_equipment($input)
    {
        if (!isset($input["user_id"]) || !isset($input["inv_id"])) {
            throw new InvalidRequestBody();
        }
        $user_id = $input["user_id"];
        $inv_id = $input["inv_id"];

        //인벤토리의 해당 위치의 장비 가져오기
        $result = $this->inventoryRepository->select_inventory_single_item($user_id, $inv_id);
        $equipment = Equipment::Deserialize($result);
        if (!$equipment->is_equipped) throw new SameEquippedState();
        else $equipment->is_equipped = 0;

        $updated_equipment = $this->inventoryRepository->update_equipment($user_id, $inv_id, $equipment);

        return $this->inventoryRepository->select_inventory($input["user_id"]);
    }

    /**
     * @throws InvalidRequestBody|ExcessMaxDurability|UnknownItemType|InvalidRepairEquipment|PearlShortage|GoldShortage|InvalidError|UnknownUser
     */
    public function repair_equipment($input)
    {
        if (!isset($input["user_id"]) || !isset($input["inv_id"])) {
            throw new InvalidRequestBody();
        }
        $user_id = $input["user_id"];
        $inv_id = $input["inv_id"];

        //인벤토리의 해당 위치의 장비 가져오기
        $result = $this->inventoryRepository->select_inventory_single_item($user_id, $inv_id);
        $equipment = Equipment::Deserialize($result);

        if ($result["item_type_id"] == 4 || $result["item_type_id"] == 7) {
            //해당 채비의 최대 내구도 구해서 세팅해주기
            $equipment_data = $this->inventoryRepository->select_equip_data($equipment);
            $equipment_repair_data = $this->inventoryRepository->select_equip_repair_data($equipment->item_type_id);

            if ($equipment->durability + $equipment_repair_data["increase_durability"] <= $equipment_data["max_durability"])
                $equipment->durability += $equipment_repair_data["increase_durability"];
            else throw new ExcessMaxDurability();

            //재화 소비
            $curr_user = $this->userRepository->select_user($input);
            switch ($equipment_repair_data["need_item_type_id"]) {
                case 1:
                    $curr_user["gold"] -= $equipment_repair_data["need_count"];
                    if ($curr_user["gold"] < 0) throw new GoldShortage();
                    break;
                case 2:
                    $curr_user["pearl"] -= $equipment_repair_data["need_count"];
                    if ($curr_user["pearl"] < 0) throw new PearlShortage();
                    break;
            }
            $updated_user = $this->userRepository->update_user($curr_user);

            //장비 업데이트
            $updated_equipment = $this->inventoryRepository->update_equipment($user_id, $inv_id, $equipment);

            //scribe - asset
            $this->scribeRepository->AssetLog($updated_user, $equipment_repair_data["need_item_type_id"], $equipment_repair_data["need_count"] * (-1), "repair_equipment");

            return $updated_equipment;
        } else throw new InvalidRepairEquipment();
    }

    /**
     * @throws InvalidRequestBody|UnknownUser
     */
    public function select_equipSlot($input)
    {
        if (!isset($input["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->inventoryRepository->select_equipSlot($input["user_id"]);
    }
}
