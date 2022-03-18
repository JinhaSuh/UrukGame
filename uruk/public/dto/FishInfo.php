<?php

namespace dto;

use JsonDeserializer;

require_once __DIR__ . '/../JsonDeserializer.php';

class FishInfo extends JsonDeserializer
{
    public int $tank_id;
    public int $user_id;
    public int $fish_id;
    public int $length;
    public \DateTime $caught_time;

}
