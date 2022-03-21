<?php

namespace App\exception;

class InvalidRepairEquipment extends MyException
{

    public function __construct()
    {
        $this->message = "내구도 수리가 가능한 장비가 아닙니다.";
        $this->code = 629;
    }
}
