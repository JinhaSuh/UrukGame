<?php

namespace exception;

class MaxLevelException extends \Exception
{

    public function __construct()
    {
        $this->message = "이미 최대 레벨입니다.";
        $this->code = 604;
    }
}
