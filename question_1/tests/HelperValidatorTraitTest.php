<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Http\Traits\HelperValidatorTrait;
use Carbon\Carbon;

class HelperValidatorTraitTest extends TestCase
{
    use HelperValidatorTrait;

    /**
     * Data provider for isValidInterval
     * 
     * @return array
     */
    public function dataProviderIsValidInterval()
    {
        return [
            ['08:00', '09:00', false],
            ['10:00', '12:00', true],
            ['08:00', '12:00', true],
            ['15:30', '17:30', true],
            ['15:30', '16:30', false],
        ];
    }

    /**
     * Data provider for isAvailableIntervalExists
     * 
     * @return array
     */
    public function dataProviderIsAvailableIntervalExists()
    {
        $bookings = [
            (object) [
                'start' => Carbon::createFromFormat('H:i', '09:00'),
                'end' => Carbon::createFromFormat('H:i', '11:00'),
            ],
            (object) [
                'start' => Carbon::createFromFormat('H:i', '11:00'),
                'end' => Carbon::createFromFormat('H:i', '15:00'),
            ],
            (object) [
                'start' => Carbon::createFromFormat('H:i', '20:00'),
                'end' => Carbon::createFromFormat('H:i', '22:00'),
            ]
        ];

        return [
            [$bookings, '08:00', '10:00', false],
            [$bookings, '15:00', '17:00', true],
            [$bookings, '17:00', '21:00', false],
            [$bookings, '08:00', '22:00', false],
            [$bookings, '16:30', '19:30', true],
        ];
    }

    /**
     * Test isValidInterval function
     *
     * @param string $start       Start Time
     * @param string $end         End Time
     * @param bool   $expectation Expectation
     *
     * @dataProvider dataProviderIsValidInterval
     * 
     * @return void
     */
    public function testIsValidInterval($start, $end, $expectation)
    {
        $this->assertEquals($expectation, $this->isValidInterval($start, $end));
    }

    /**
     * Test isAvailableIntervalExists function
     *
     * @param array  $bookings    Bookings Collection  
     * @param string $start       Start Time
     * @param string $end         End Time
     * @param bool   $expectation Expectation
     *
     * @dataProvider dataProviderIsAvailableIntervalExists
     * 
     * @return void
     */
    public function testIsAvailableIntervalExists($bookings, $start, $end, $expectation)
    {
        $this->assertEquals($expectation, $this->isAvailableIntervalExists($bookings, $start, $end));
    }
}
