<?php

namespace App\dto;

class FishInfo extends JsonDeserializer
{
    public int $tank_id;
    public int $user_id;
    public int $fish_id;
    public int $length;
    public \DateTime $caught_time;

}
