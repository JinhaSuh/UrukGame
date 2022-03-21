<?php

namespace App\exception;

class UnknownHiveID extends MyException
{

    public function __construct()
    {
        $this->message = "알 수 없는 HiveID입니다.";
        $this->code = 507;
    }
}
