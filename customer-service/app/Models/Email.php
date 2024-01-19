<?php

namespace App\Models;

class Email
{
    public function __construct(public readonly string $address)
    {
        if (!filter_var($this->address, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf("Email address %s isn't considered valid.", $this->address));
        }
    }
}
