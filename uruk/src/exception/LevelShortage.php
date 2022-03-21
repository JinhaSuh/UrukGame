<?php

namespace App\exception;

class LevelShortage extends MyException
{

    public function __construct()
    {
        $this->message = "입장 가능한 레벨보다 낮습니다.";
        $this->code = 614;
    }
}
