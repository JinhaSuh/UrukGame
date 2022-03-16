<?php

namespace exception;

class InvalidRequestBody extends \Exception
{

    public function __construct()
    {
        $this->message = "요청 형식이 잘못되었습니다.";
        $this->code = 510;
    }
}
