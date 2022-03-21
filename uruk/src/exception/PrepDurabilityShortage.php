<?php

namespace App\exception;

class PrepDurabilityShortage extends MyException
{
    public function __construct()
    {
        $this->message = "채비의 내구도가 부족합니다.";
        $this->code = 612;
    }
}
