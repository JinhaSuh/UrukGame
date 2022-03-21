<?php

namespace App\dto;

class Equipment extends JsonDeserializer
{
    public int $inv_id;
    public int $user_id;
    public int $item_type_id;
    public int $item_id;
    public int $item_count;
    public int $durability;
    public int $is_equipped;

}
