<?php

namespace App\service;

use App\repository\WaterTankRepository;

class WaterTankService
{
    private WaterTankRepository $waterTankRepository;

    public function __construct()
    {
        $this->waterTankRepository = new WaterTankRepository();
    }

    public function select_water_tank($user)
    {
        if (!isset($user["user_id"])) {
            throw new InvalidRequestBody();
        }

        return $this->waterTankRepository->select_water_tank($user["user_id"]);
    }

}
