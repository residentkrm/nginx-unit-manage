<?php

namespace App\Exceptions\Unit;

use Exception;

class ApplicationAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Application '{$name}' already exists.");
    }
}
