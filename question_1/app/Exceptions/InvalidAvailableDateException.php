<?php

namespace App\Exceptions;

use Exception;

class InvalidAvailableDateException extends Exception
{
    public function render($request)
    {
        return response(['error' => 'Invalid date or less than today'], 400);
    }
}
