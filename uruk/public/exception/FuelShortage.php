<?php

namespace exception;

class FuelShortage extends \Exception
{

    public function __construct()
    {
        $this->message = "배의 연료가 부족합니다.";
        $this->code = 610;
    }
}
