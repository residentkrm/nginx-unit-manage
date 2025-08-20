<?php

namespace App\Exceptions\Unit;

use Exception;

class ListenerAlreadyExistsException extends Exception
{
    public function __construct(string $address)
    {
        parent::__construct("Listener '{$address}' already exists.");
    }
}

