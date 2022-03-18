<?php

namespace repository;

use DB\Config\Game_Data_Database;
use DB\Config\Plan_Data_Database;
use dto\Equipment;
use exception\InvalidUpgradeEquipment;
use exception\UnknownItemType;
use PDO;

require_once __DIR__ . '/../dto/Equipment.php';
require_once __DIR__ . '/../config/Game_Data_Database.php';
require_once __DIR__ . '/../config/Plan_Data_Database.php';
require_once __DIR__ . '/../exception/InvalidUpgradeEquipment.php';
require_once __DIR__ . '/../exception/UnknownItemType.php';

class InventoryRepository
{
    private PDO $game_db_conn;
    private PDO $plan_db_conn;

    public function __construct()
    {
        $game_db = new Game_Data_Database();
        $plan_db = new Plan_Data_Database();
        $this->game_db_conn = $game_db->getConnection();
        $this->plan_db_conn = $plan_db->getConnection();
    }

    public function select_inventory($user_id)
    {
        $sql = "SELECT * FROM inventory WHERE user_id=:userId ORDER BY inv_id";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_inventory_single_item(int $user_id, int $inv_id)
    {
        $sql = "SELECT * FROM inventory WHERE user_id=:userId AND inv_id =:invId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':invId', $inv_id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert_equipment(int $user_id, Equipment $equipment)
    {
        $sql = "INSERT INTO inventory (user_id, item_type_id, item_id, item_count, durability, is_equipped) VALUES (:userId, :itemTypeId, :itemId, :itemCount, :durability, 0)";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':itemTypeId', $equipment->item_type_id);
        $stmt->bindParam(':itemId', $equipment->item_id);
        $stmt->bindParam(':itemCount', $equipment->item_count);
        $stmt->bindParam(':durability', $equipment->durability);

        $stmt->execute();
        return $this->select_inventory($user_id);
    }

    public function update_equipment(int $user_id, int $inv_id, Equipment $equipment)
    {
        $sql = "UPDATE inventory SET item_id=:itemId, item_count=:itemCount, durability=:durability, is_equipped=:isEquipped WHERE user_id=:userId AND inv_id =:invId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':itemId', $equipment->item_id);
        $stmt->bindParam(':itemCount', $equipment->item_count);
        $stmt->bindParam(':durability', $equipment->durability);
        $stmt->bindParam(':isEquipped', $equipment->is_equipped);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':invId', $inv_id);

        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return $equipment;
    }

    public function select_equip_upgrade_data(int $item_type, int $grade_id, int $step)
    {
        $sql = "SELECT * FROM equip_upgrade_data WHERE item_type_id=:itemTypeId AND grade_id=:gradeId AND step=:step";

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':itemTypeId', $item_type);
        $stmt->bindParam(':gradeId', $grade_id);
        $stmt->bindParam(':step', $step);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @throws UnknownItemType
     */
    public function select_equip_info(Equipment $equipment)
    {
        switch ($equipment->item_type_id) {
            case 4:
                $sql = "SELECT * FROM fishing_rod_data WHERE fishing_rod_id=:itemId";
                break;
            case 5:
                $sql = "SELECT * FROM weight_data WHERE weight_id=:itemId";
                break;
            case 6:
                $sql = "SELECT * FROM bait_data WHERE bait_id=:itemId";
                break;
            case 7:
                $sql = "SELECT * FROM reel_data WHERE reel_id=:itemId";
                break;
            case 8:
                $sql = "SELECT * FROM hook_data WHERE hook_id=:itemId";
                break;
            case 9:
                $sql = "SELECT * FROM fishing_line_data WHERE fishing_line_id=:itemId";
                break;
            default:
                throw new UnknownItemType();
        }

        $stmt = $this->plan_db_conn->prepare($sql);
        $stmt->bindParam(':itemId', $equipment->item_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @throws InvalidUpgradeEquipment
     */
    public function select_next_step_equip_data(int $item_type_id, int $cur_grade, int $next_step)
    {
        switch ($item_type_id) {
            case 4:
                $sql = "SELECT * FROM fishing_rod_data WHERE grade_id=:gradeId AND step=:step";
                $stmt = $this->plan_db_conn->prepare($sql);
                $stmt->bindParam(':gradeId', $cur_grade);
                $stmt->bindParam(':step', $next_step);
                break;
            case 7:
                $sql = "SELECT * FROM reel_data WHERE grade_id=:gradeId AND number=:number";
                $stmt = $this->plan_db_conn->prepare($sql);
                $stmt->bindParam(':gradeId', $cur_grade);
                $stmt->bindParam(':number', $next_step);
                break;
            case 9:
                $sql = "SELECT * FROM fishing_line_data WHERE number=:number";
                $stmt = $this->plan_db_conn->prepare($sql);
                $stmt->bindParam(':number', $next_step);
                break;
            default:
                throw new InvalidUpgradeEquipment();
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function select_equipSlot(int $user_id)
    {
        $sql = "SELECT * FROM inventory WHERE user_id=:userId AND is_equipped=1 ORDER BY item_type_id";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function select_equipSlot_item(int $user_id, int $item_type_id)
    {
        $sql = "SELECT * FROM inventory WHERE user_id=:userId AND is_equipped=1 AND item_type_id=:itemTypeId";

        $stmt = $this->game_db_conn->prepare($sql);
        $stmt->bindParam(':userId', $user_id);
        $stmt->bindParam(':itemTypeId', $item_type_id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
