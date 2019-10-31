<?php

namespace App\Message;

class SmsNotification
{
    private $body;
    private $number;

    public function __construct(string $body, string $number)
    {
        $this->body = $body;
        $this->number = $number;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getNumber(): string{
        return $this->number;
    }
}