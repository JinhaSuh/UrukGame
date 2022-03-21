<?php

namespace App\exception;

class SuppressFailed extends MyException
{
    public function __construct()
    {
        $this->message = "제압에 실패하였습니다.";
        $this->code = 625;
    }
}
