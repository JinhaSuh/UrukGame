<?php

namespace App\exception;

class UnknownBoat extends MyException
{

    public function __construct()
    {
        $this->message = "알 수 없는 배입니다.";
        $this->code = 511;
    }
}
