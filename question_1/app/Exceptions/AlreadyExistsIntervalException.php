<?php

namespace App\Exceptions;

use Exception;

class AlreadyExistsIntervalException extends Exception
{
    public function render($request)
    {
        return response(['error' => 'The given interval is already exists'], 400);
    }
}
