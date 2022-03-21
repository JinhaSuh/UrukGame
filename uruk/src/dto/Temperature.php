<?php

namespace App\dto;

class Temperature extends JsonDeserializer
{
    public int $default_temperature;
    public int $min_temperature;
    public int $max_temperature;
    public int $iter_term;
    public int $change_range;

}
