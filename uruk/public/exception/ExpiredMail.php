<?php

namespace exception;

class ExpiredMail extends \Exception
{

    public function __construct()
    {
        $this->message = "수령기한이 지난 메일입니다.";
        $this->code = 622;
    }
}
