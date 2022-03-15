<?php

namespace exception;

class InvalidError extends \Exception
{

    public function __construct()
    {
        $this->message = "알 수 없는 오류입니다.";
        $this->code = 501;
    }
}
