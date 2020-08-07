<?php

namespace Question\Controllers;

class AppController
{
    /**
     * Initialize application logic
     * and run required steps according to
     * the predefined algorithm
     * 
     * @return void
     */
    public static function init()
    {
        try {
            $console = new ConsoleController();
            $solution = new SimilaritiesSumController();

            $count = $console->count();
            $inputs = $console->read($count);

            foreach ($inputs as $input) {
                $result = $solution->findSimilaritiesSum($input);
                $console->print($result);
            }
        } catch (\Exception $exception) {
            $console->error($exception);
        }
    }
}
