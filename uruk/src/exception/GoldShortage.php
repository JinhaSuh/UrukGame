<?php

namespace App\exception;

class GoldShortage extends MyException
{

    public function __construct()
    {
        $this->message = "골드가 부족합니다.";
        $this->code = 607;
    }
}
