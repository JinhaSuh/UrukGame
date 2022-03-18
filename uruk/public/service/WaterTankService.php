<?php

namespace service;

use exception\UserException;
use repository\WaterTankRepository;

require_once __DIR__ . '/../repository/WaterTankRepository.php';

class WaterTankService
{
    private WaterTankRepository $waterTankRepository;

    public function __construct()
    {
        $this->waterTankRepository = new WaterTankRepository();
    }

    /**
     * @throws UserException
     */
    public function select_water_tank($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->waterTankRepository->select_water_tank($user["user_id"]);
    }

}
