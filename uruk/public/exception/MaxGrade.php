<?php

namespace exception;

class MaxGrade extends \Exception
{

    public function __construct()
    {
        $this->message = "이미 최대 등급입니다.";
        $this->code = 605;
    }
}
