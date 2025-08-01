<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nginx Unit API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to Nginx Unit Control API
    |
    */

    'api_url' => env('UNIT_API_URL', 'unix:/opt/homebrew/var/run/unit/control.sock'),
    
    'api_timeout' => env('UNIT_API_TIMEOUT', 10),
    
    'verify_ssl' => env('UNIT_VERIFY_SSL', false),
];

