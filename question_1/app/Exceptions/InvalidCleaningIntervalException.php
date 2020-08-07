<?php

namespace App\Exceptions;

use Exception;

class InvalidCleaningIntervalException extends Exception
{
    public function render($request)
    {
        return response(['error' => 'Cleaning interval must be 2 or 4 hours'], 400);
    }
}
