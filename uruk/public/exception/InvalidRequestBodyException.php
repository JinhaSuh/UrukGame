<?php

namespace exception;

class InvalidRequestBodyException extends \Exception
{

    public function __construct()
    {
        $this->message = "요청 형식이 잘못되었습니다.";
        $this->code = 510;
    }
}
