<?php

namespace exception;

class EquipmentNotReady extends \Exception
{

    public function __construct()
    {
        $this->message = "채비가 완료되지 않았습니다.";
        $this->code = 616;
    }
}
