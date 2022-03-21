<?php

namespace App\exception;

class UpgradeFailure extends MyException
{

    public function __construct()
    {
        $this->message = "강화에 실패하였습니다.";
        $this->code = 602;
    }
}
