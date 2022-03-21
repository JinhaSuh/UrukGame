<?php

namespace App\exception;

class UnknownFish extends MyException
{

    public function __construct()
    {
        $this->message = "알 수 없는 물고기입니다.";
        $this->code = 508;
    }
}
