<?php

namespace App\exception;

class InvalidUserState extends MyException
{

    public function __construct()
    {
        $this->message = "요청한 행동을 할 수 없는 상태입니다.";
        $this->code = 627;
    }
}
