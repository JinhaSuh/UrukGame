<?php

namespace exception;

class AlreadyEquippedType extends \Exception
{

    public function __construct()
    {
        $this->message = "같은 종류의 채비가 이미 장착중입니다.";
        $this->code = 621;
    }
}
