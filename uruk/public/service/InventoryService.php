<?php

namespace service;

use dto\Equipment;
use exception\InvalidError;
use exception\InvalidUpgradeEquipmentException;
use exception\MaxLevelException;
use http\Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use exception\UserException;
use repository\InventoryRepository;
use dto\User;
use dto\Inventory;
use repository\UserRepository;

require_once __DIR__ . '/../repository/InventoryRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../dto/User.php';
require_once __DIR__ . '/../dto/Equipment.php';
require_once __DIR__ . '/../dto/Inventory.php';
require_once __DIR__ . '/../exception/UserException.php';
require_once __DIR__ . '/../exception/MaxLevelException.php';
require_once __DIR__ . '/../exception/InvalidUpgradeEquipmentException.php';


class InventoryService
{
    private InventoryRepository $inventoryRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->inventoryRepository = new InventoryRepository();
        $this->userRepository = new UserRepository();
    }

    public function select_inventory($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBodyException();
        }

        return $this->inventoryRepository->select_inventory($user);
    }

    /**
     * @throws UserException
     * @throws \Exception
     */
    public function upgrade_equipment($input)
    {
        if (!isset($input["user_id"]) || !isset($input["inv_id"])) {
            throw new InvalidRequestBodyException();
        }
        $user_id = $input["user_id"];
        $inv_id = $input["inv_id"];

        //인벤토리의 해당 위치의 장비 가져오기
        $result = $this->inventoryRepository->select_inventory_single_item($user_id, $inv_id);
        $equipment = Equipment::Deserialize($result);

        //장비의 상세 info 가져오기
        if ($equipment->item_type_id == 4 || $equipment->item_type_id == 7 || $equipment->item_type_id == 9)
            $equipment_info = $this->inventoryRepository->select_equip_info($equipment);
        else //업그레이드 가능한 타입이 아닐 시
            throw new InvalidUpgradeEquipmentException();

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
                throw new \Exception("업그레이드 불가능한 장비입니다.");
        }

        //다음 단계의 장비 있는지 확인
        $next_step_equipment_info = $this->inventoryRepository->select_next_step_equip_info($equipment->item_type_id, $cur_grade, $cur_step + 1);
        //없을 경우
        if (empty($next_step_equipment_info)) throw new MaxLevelException();

        //다음 단계로 업그레이드에 필요한 재화 기획 데이터 가져오기
        $equip_upgrade_data = $this->inventoryRepository->select_equip_upgrade_data($equipment->item_type_id, $equipment_info["grade_id"], $equipment_info["step"] + 1);
        $need_goods_type = $equip_upgrade_data["need_item_type_id"];
        $need_goods_count = $equip_upgrade_data["need_count"];

        //유저 정보 가져오기
        $input_user = array();
        $input_user["user_id"]= $user_id;
        $user = $this->userRepository->select_user($input_user);

        //재화 소비
        switch ($need_goods_type) {
            case 1:
                $user["gold"] -= $need_goods_count;
                if ($user->gold < 0) throw new UserException("골드가 부족합니다.", 607);
                break;
            case 2:
                $user["pearl"] -= $need_goods_count;
                if ($user["pearl"] < 0) throw new UserException("골드가 부족합니다.", 607);
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
                throw new InvalidUpgradeEquipmentException();
        }

        //TODO : 인벤토리 장비 업데이트
        $upgraded_equipment = $this->inventoryRepository->update_equipment($user_id, $inv_id, $equipment);
        return $upgraded_equipment;
    }
}
