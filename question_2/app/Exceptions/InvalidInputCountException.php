<?php

namespace Question\Exceptions;

use \Exception;

class InvalidInputCountException extends Exception
{
    protected $message = 'Invalid input count is given';
}
