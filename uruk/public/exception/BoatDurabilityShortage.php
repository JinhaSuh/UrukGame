<?php

namespace exception;

class BoatDurabilityShortage extends \Exception
{

    public function __construct()
    {
        $this->message = "배의 내구도가 부족합니다.";
        $this->code = 611;
    }
}
