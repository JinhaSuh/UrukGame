<?php

namespace App\exception;

class UnknownItemType extends MyException
{

    public function __construct()
    {
        $this->message = "알 수 없는 아이템타입입니다.";
        $this->code = 503;
    }
}
