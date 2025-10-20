<?php

namespace App\Exceptions\Unit;

use Exception;

class ListenerActiveException extends Exception
{
    public function __construct()
    {
        parent::__construct('Please deactivate the listener before deleting it.');
    }
}
