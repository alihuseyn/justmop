<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Cleaner;
use App\Models\Company;
use App\Models\Booking;
use Carbon\Carbon;

class AvailableTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test available endpoint
     * Check for past date enter exception
     *
     * @return void
     */
    public function testAvailableEndpoint()
    {
        factory(Cleaner::class, 1)->create();
        factory(Company::class, 1)->create();

        // Test past date
        $response = $this->get('/cleaners/available/1970-01-01');
        $response->seeStatusCode(400);
        $response->seeJson([
            'error' => 'Invalid date or less than today'
        ]);

        // Test invalid date
        $response = $this->get('/cleaners/available/70-13-01');
        $response->seeStatusCode(422);

        // Test with correct body
        $tomorrow = Carbon::tomorrow();

        Booking::forceCreate([
            'cleaner_id' => 1,
            'company_id' => 1,
            'start' => '16:00',
            'end' => '20:00',
            'date' => $tomorrow->format('Y-m-d')
        ]);

        $response = $this->get('/cleaners/available/' . $tomorrow->format('Y-m-d'));
        $response->seeStatusCode(200);
        $cleaner = Cleaner::find(1);
        $response->seeJson([
            [
                'id' => 1,
                'name' => $cleaner->name,
                'available' => [
                    [
                        'start' => '08:00',
                        'end' => '16:00'
                    ],
                    [
                        'start' => '20:00',
                        'end' => '22:00'
                    ]
                ]
            ]
        ]);
        
    }
}
