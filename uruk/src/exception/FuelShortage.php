<?php

namespace App\exception;

class FuelShortage extends MyException
{

    public function __construct()
    {
        $this->message = "배의 연료가 부족합니다.";
        $this->code = 610;
    }
}
