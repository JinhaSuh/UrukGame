<?php

namespace exception;

class PrepDurabilityShortage extends \Exception
{
    public function __construct()
    {
        $this->message = "채비의 내구도가 부족합니다.";
        $this->code = 612;
    }
}
