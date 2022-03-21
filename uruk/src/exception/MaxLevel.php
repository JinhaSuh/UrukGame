<?php

namespace App\exception;

class MaxLevel extends MyException
{

    public function __construct()
    {
        $this->message = "이미 최대 레벨입니다.";
        $this->code = 604;
    }
}
