<?php

namespace App\dto;

class Tide extends JsonDeserializer
{
    public int $default_tide;
    public int $min_tide;
    public int $max_tide;
    public int $iter_term;
    public int $splash_time;
    public int $change_range;

}
