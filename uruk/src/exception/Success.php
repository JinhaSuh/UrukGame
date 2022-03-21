<?php

namespace App\exception;

class Success extends MyException
{
    public function __construct()
    {
        $this->message = "Success";
        $this->code = 200;
    }
}
