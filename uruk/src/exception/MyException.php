<?php

namespace App\exception;

class MyException extends \Exception implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'message' => $this->message
        ];
    }
}
