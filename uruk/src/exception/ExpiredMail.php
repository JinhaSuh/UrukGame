<?php

namespace App\exception;

class ExpiredMail extends MyException
{

    public function __construct()
    {
        $this->message = "수령기한이 지난 메일입니다.";
        $this->code = 622;
    }
}
