<?php

namespace Question\Controllers;

use Question\Exceptions\InvalidInputCountException;
use Codedungeon\PHPCliColors\Color;

class ConsoleController
{
    /**
     * Read input count from console
     * and return it
     *
     * @throws InvalidInputCountException
     * 
     * @return int
     */
    public function count() : int
    {
        $count = intval(readline("Please enter input count: "));

        if (!is_int($count) || $count <= 0) {
            throw new InvalidInputCountException;
        }

        return $count;
    }

    /**
     * Read from console with the amount of
     * given count value 
     * 
     * @param int $count Read count
     *
     * @return array Inputs
     */
    public function read($count) : array
    {
        $inputs = [];
        for ($i = 0; $i < $count; $i += 1) {
            array_push($inputs, readline());
        }

        return $inputs;
    }

    /**
     * Print out the given content
     * 
     * @param mixed $output Output to be printed
     *
     * @return void
     */
    public function print($output)
    {
        echo Color::GREEN, $output, Color::RESET, PHP_EOL;
    }

    /**
     * Print out exception
     *
     * @param \Exception $error Exception object instance
     *
     * @return void
     */
    public function error($error)
    {
        echo Color::RED, $error->getMessage(), Color::RESET, PHP_EOL;
    }
}
