<?php

namespace exception;

class SuppressFailed extends \Exception
{
    public function __construct()
    {
        $this->message = "제압에 실패하였습니다.";
        $this->code = 625;
    }
}
