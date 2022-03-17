<?php

namespace exception;

class UnknownMail extends \Exception
{

    public function __construct()
    {
        $this->message = "알 수 없는 메일입니다.";
        $this->code = 506;
    }
}
