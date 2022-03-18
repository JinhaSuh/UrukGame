<?php

namespace dto;

use JsonDeserializer;

require_once __DIR__ . '/../JsonDeserializer.php';

class Fish extends JsonDeserializer
{
    public int $fish_id;
    public string $fish_name;
    public int $rarity;
    public int $min_length;
    public int $max_length;
    public int $price;
    public int $exp;
}
