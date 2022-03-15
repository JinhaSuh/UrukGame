<?php

namespace dto;

use JsonDeserializer;

require_once __DIR__ . '/../JsonDeserializer.php';

class Wind extends JsonDeserializer
{
    public int $default_volume;
    public int $min_volume;
    public int $max_volume;
    public int $iter_term;
    public int $change_range;

}
