<?php

namespace exception;

class UnknownFish extends \Exception
{

    public function __construct()
    {
        $this->message = "알 수 없는 물고기입니다.";
        $this->code = 508;
    }
}
