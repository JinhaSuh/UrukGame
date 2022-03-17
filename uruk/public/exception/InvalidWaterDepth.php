<?php

namespace exception;

class InvalidWaterDepth extends \Exception
{

    public function __construct()
    {
        $this->message = "최대 수심보다 깊게 내릴 수 없습니다.";
        $this->code = 617;
    }
}
