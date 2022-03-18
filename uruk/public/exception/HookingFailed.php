<?php

namespace exception;

class HookingFailed extends \Exception
{

    public function __construct()
    {
        $this->message = "후킹에 실패하였습니다.";
        $this->code = 624;
    }
}
