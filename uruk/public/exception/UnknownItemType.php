<?php

namespace exception;

class UnknownItemType extends \Exception
{

    public function __construct()
    {
        $this->message = "알 수 없는 아이템타입입니다.";
        $this->code = 503;
    }
}
