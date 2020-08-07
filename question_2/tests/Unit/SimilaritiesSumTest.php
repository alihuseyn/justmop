<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Question\Controllers\SimilaritiesSumController;
use Question\Exceptions\EmptyInputException;

class SimilaritiesSumTest extends TestCase
{
    /**
     * Data provider for function
     *
     * @return array
     */
    public function findSimilaritiesSumDataProvider()
    {
        return [
            ['ababaa', 11],
            ['aa', 3],
            ['acaa', 6],
            ['alihuseyn', 9]
        ];
    }

    /**
     * Test whether find similarities sum return correct result or not
     * @param string $input Input string
     * @param int $expectation Expected result from function
     *
     * @dataProvider findSimilaritiesSumDataProvider
     * 
     * @return void
     */
    public function testFindSimilaritiesSumReturnResult($input, $expectation)
    {
        $solution = new SimilaritiesSumController();
        $this->assertEquals($expectation, $solution->findSimilaritiesSum($input));
    }

    /**
     * Test whether exception is thrown in empty input
     *
     * @return void
     */
    public function testFindSimilaritiesSumThrowsException()
    {
        $solution = new SimilaritiesSumController();
        $this->expectException(EmptyInputException::class);
        $solution->findSimilaritiesSum('');
    }
}
