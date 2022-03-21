<?php

namespace App\exception;

class UnknownMap extends MyException
{

    public function __construct()
    {
        $this->message = "알 수 없는 맵입니다.";
        $this->code = 505;
    }
}
