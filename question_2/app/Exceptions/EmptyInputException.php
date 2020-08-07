<?php

namespace Question\Exceptions;

use \Exception;

class EmptyInputException extends Exception
{
    protected $message = 'Input cannot be empty';
}
