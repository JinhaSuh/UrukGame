<?php

namespace App\exception;

class InvalidFishingState extends MyException
{

    public function __construct()
    {
        $this->message = "낚시 가능한 상태가 아닙니다.";
        $this->code = 623;
    }
}
