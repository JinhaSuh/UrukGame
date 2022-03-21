<?php

namespace App\exception;

class ExcessMaxFatigue extends MyException
{

    public function __construct()
    {
        $this->message = "최대 피로도를 초과하여 충전할 수 없습니다.";
        $this->code = 618;
    }
}
