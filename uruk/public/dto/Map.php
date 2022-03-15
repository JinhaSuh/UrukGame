<?php

namespace dto;

use JsonDeserializer;

require_once __DIR__ . '/../JsonDeserializer.php';

class Map extends JsonDeserializer
{
    public int $map_id;
    public string $map_name;
    public int $max_water_depth;
    public int $distance;
    public int $level_limit;
    public int $departure_cost;
    public int $departure_time;
    public int $reduce_fatigue_per_min;
    public int $reduce_durability_per_meter;

}
