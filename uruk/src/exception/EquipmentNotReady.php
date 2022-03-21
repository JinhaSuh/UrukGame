<?php

namespace App\exception;

class EquipmentNotReady extends MyException
{

    public function __construct()
    {
        $this->message = "채비가 완료되지 않았습니다.";
        $this->code = 616;
    }
}
