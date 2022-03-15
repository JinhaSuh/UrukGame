<?php

namespace dto;

use JsonDeserializer;

require_once __DIR__ . '/../JsonDeserializer.php';

class Equipment extends JsonDeserializer
{
    public int $item_type_id;
    public int $item_id;
    public int $item_count;
    public int $durability;
    public int $is_equipped;
}
