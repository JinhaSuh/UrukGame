<?php

namespace App\exception;

class ExcessMaxFuel extends MyException
{

    public function __construct()
    {
        $this->message = "최대 연료를 초과하여 충전할 수 없습니다.";
        $this->code = 630;
    }
}
