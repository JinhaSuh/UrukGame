<?php

namespace exception;

class LevelShortage extends \Exception
{

    public function __construct()
    {
        $this->message = "입장 가능한 레벨보다 낮습니다.";
        $this->code = 614;
    }
}
