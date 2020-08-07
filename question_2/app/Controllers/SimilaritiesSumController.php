<?php

namespace Question\Controllers;

use Question\Exceptions\EmptyInputException;

class SimilaritiesSumController
{
    /**
     * Find similarities sum for the given input over its suffix
     * 
     * @param string $input [description]
     *
     * @throws EmptyInputException
     * 
     * @return int result of operation
     */
    public function findSimilaritiesSum(string $input) : int
    {
        if (empty($input)) {
            throw new EmptyInputException;
        }

        $result = 0;
        for ($i = 0; $i < strlen($input); $i += 1) {
            for ($j = 0, $k = $i; $k < strlen($input); $k += 1, $j += 1) {
                if ($input[$j] == $input[$k]) {
                    $result += 1;
                } else {
                    break;
                }
            }
        }

        return $result;
    }
}
