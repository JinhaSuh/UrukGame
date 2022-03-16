<?php

namespace exception;

class UserException extends \Exception
{
    public function __construct()
    {
        $this->message = "알 수 없는 유저입니다.";
        $this->code = 507;
    }
}
