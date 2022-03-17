<?php

namespace exception;

class UpgradeFailure extends \Exception
{

    public function __construct()
    {
        $this->message = "강화에 실패하였습니다.";
        $this->code = 602;
    }
}
