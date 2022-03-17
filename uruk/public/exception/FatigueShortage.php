<?php

namespace exception;

class FatigueShortage extends \Exception
{

    public function __construct()
    {
        $this->message = "피로도가 부족합니다.";
        $this->code = 609;
    }
}
