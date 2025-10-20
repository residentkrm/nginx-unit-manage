<?php

namespace App\Exceptions\Unit;

use Exception;

class ApplicationActiveException extends Exception
{
    public function __construct()
    {
        parent::__construct('Please deactivate the application before deleting it.');
    }
}
