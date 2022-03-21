<?php

namespace App\exception;

class SameEquippedState extends MyException
{
    public function __construct()
    {
        $this->message = "장착 또는 장착해제 가능한 상태가 아닙니다.";
        $this->code = 620;
    }
}
