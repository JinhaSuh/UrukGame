<?php

namespace exception;

class InvalidUpgradeEquipmentException extends \Exception
{

    public function __construct()
    {
        $this->message = "업그레이드 가능한 채비 타입이 아닙니다.";
        $this->code = 619;
    }
}
