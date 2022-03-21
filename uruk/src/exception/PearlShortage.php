<?php

namespace App\exception;

class PearlShortage extends MyException
{
    public function __construct()
    {
        $this->message = "진주가 부족합니다.";
        $this->code = 608;
    }
}
