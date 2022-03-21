<?php

namespace App\service;

use App\dto\Map;
use App\repository\MapRepository;

class MapService
{
    private MapRepository $mapRepository;

    public function __construct()
    {
        $this->mapRepository = new MapRepository();
    }

    public function select_maps()
    {
        $map_data = $this->mapRepository->select_map_list();
        $map_list = array();
        for ($i = 0; $i < count($map_data); $i++) {
            $map_list[$i] = Map::Deserialize($map_data[$i]);
        }

        return $map_list;
    }
}
