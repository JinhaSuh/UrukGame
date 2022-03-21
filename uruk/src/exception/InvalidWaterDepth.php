<?php

namespace App\exception;

class InvalidWaterDepth extends MyException
{

    public function __construct()
    {
        $this->message = "최대 수심보다 깊게 내릴 수 없습니다.";
        $this->code = 617;
    }
}
