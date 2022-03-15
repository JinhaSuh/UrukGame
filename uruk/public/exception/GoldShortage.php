<?php

namespace exception;

class GoldShortage extends \Exception
{

    public function __construct()
    {
        $this->message = "골드가 부족합니다.";
        $this->code = 607;
    }
}
