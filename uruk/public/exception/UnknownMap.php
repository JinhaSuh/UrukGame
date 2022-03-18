<?php

namespace exception;

class UnknownMap extends \Exception
{

    public function __construct()
    {
        $this->message = "알 수 없는 맵입니다.";
        $this->code = 505;
    }
}
