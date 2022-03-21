<?php

namespace App\exception;

class FatigueShortage extends MyException
{

    public function __construct()
    {
        $this->message = "피로도가 부족합니다.";
        $this->code = 609;
    }
}
