<?php

namespace exception;

class ForcedArrival extends \Exception
{

    public function __construct()
    {
        $this->message = "내구도나 연료가 부족하여 강제 입항합니다.";
        $this->code = 626;
    }
}
