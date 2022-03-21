<?php

namespace App\exception;

class UnknownUser extends MyException
{
    public function __construct()
    {
        $this->message = "알 수 없는 유저입니다.";
        $this->code = 507;
    }
}
