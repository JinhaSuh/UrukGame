<?php

namespace service;

use exception\BoatDurabilityShortage;
use exception\EquipmentNotReady;
use exception\FatigueShortage;
use exception\FuelShortage;
use exception\InvalidFishingState;
use exception\InvalidRequestBody;
use exception\InvalidWaterDepth;
use exception\UserException;
use repository\BoatRepository;
use repository\FishingRepository;
use repository\InventoryRepository;
use repository\MapRepository;
use repository\UserRepository;

require_once __DIR__ . '/../repository/FishingRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/InventoryRepository.php';
require_once __DIR__ . '/../repository/BoatRepository.php';
require_once __DIR__ . '/../repository/MapRepository.php';
require_once __DIR__ . '/../exception/InvalidRequestBody.php';
require_once __DIR__ . '/../exception/FatigueShortage.php';
require_once __DIR__ . '/../exception/EquipmentNotReady.php';
require_once __DIR__ . '/../exception/BoatDurabilityShortage.php';
require_once __DIR__ . '/../exception/InvalidFishingState.php';
require_once __DIR__ . '/../exception/FuelShortage.php';
require_once __DIR__ . '/../exception/InvalidWaterDepth.php';

class FishingService
{
    private FishingRepository $fishingRepository;
    private UserRepository $userRepository;
    private InventoryRepository $inventoryRepository;
    private BoatRepository $boatRepository;
    private MapRepository $mapRepository;

    public function __construct()
    {
        $this->fishingRepository = new FishingRepository();
        $this->userRepository = new UserRepository();
        $this->inventoryRepository = new InventoryRepository();
        $this->boatRepository = new BoatRepository();
        $this->mapRepository = new MapRepository();
    }

    /**
     * @throws InvalidRequestBody|UserException|FatigueShortage|EquipmentNotReady|BoatDurabilityShortage|InvalidFishingState|FuelShortage|InvalidWaterDepth
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

        //배 내구도, 연료 검사
        $boat = $this->boatRepository->select_boat($input["user_id"]);
        if ($boat["map_id"] == 0) throw new InvalidFishingState();
        if ($boat["durability"] <= 0) throw new BoatDurabilityShortage();
        if ($boat["fuel"] <= 0) throw new FuelShortage();

        //수심 검사
        $map_list = $this->mapRepository->select_map_list();
        $curr_map_max_depth = $map_list[$boat["map_id"]]["max_water_depth"];
        if ($input["depth"] > $curr_map_max_depth) throw new InvalidWaterDepth();
    }
}
