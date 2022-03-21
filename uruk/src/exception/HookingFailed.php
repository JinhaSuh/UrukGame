<?php

namespace App\exception;

class HookingFailed extends MyException
{

    public function __construct()
    {
        $this->message = "후킹에 실패하였습니다.";
        $this->code = 624;
    }
}
