<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Cleaner;
use App\Models\Company;
use App\Models\Booking;
use Carbon\Carbon;

class BookingTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Run before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        factory(Cleaner::class, 1)->create();
        factory(Company::class, 1)->create();
    }

    /**
     * Test booking get and post  endpoints
     * Create new booking and will retrieve
     * to see whether response is correct.
     * Check also exception cases
     *
     * @return void
     */
    public function testBookingGetAndPostEndpoints()
    {
        // Missing parameter test case
        $response = $this->post('/cleaners/1/bookings', [
            'start' => '08:00',
            'end' => '09:00',
        ]);
        $response->seeStatusCode(422);

        $now = Carbon::now();
        if ($now->isFriday()) {
            $now->add(1, 'day');
        }

        // Invalid Interval Selection
        $response = $this->post('/cleaners/1/bookings', [
            'start' => '08:00',
            'end' => '09:00',
            'company' => 1,
            'date' => $now->format('Y-m-d')
        ]);
        $response->seeStatusCode(400);
        $response->seeJson([
            'error' => 'Cleaning interval must be 2 or 4 hours'
        ]);

        // Invalid Date Entry
        $response = $this->post('/cleaners/1/bookings', [
            'start' => '08:00',
            'end' => '10:00',
            'company' => 1,
            'date' => $now->clone()->add(-7, 'day')->format('Y-m-d')
        ]);
        $response->seeStatusCode(400);
        $response->seeJson([
            'error' => 'Invalid date or less than today'
        ]);

        // Not Found Exception
        $response = $this->post('/cleaners/4/bookings', [ // Not Exists
            'start' => '08:00',
            'end' => '10:00',
            'company' => 1, 
            'date' => $now->format('Y-m-d')
        ]);
        $response->seeStatusCode(404);

        // Correct Entry
        $response = $this->post('/cleaners/1/bookings', [
            'start' => '08:00',
            'end' => '10:00',
            'company' => 1, 
            'date' => $now->format('Y-m-d')
        ]);
        $response->seeStatusCode(201);

        // Available Entry selection
        $response = $this->post('/cleaners/1/bookings', [
            'start' => '08:00',
            'end' => '10:00',
            'company' => 1, 
            'date' => $now->format('Y-m-d')
        ]);
        $response->seeStatusCode(400);
        $response->seeJson([
            'error' => 'The given interval is already exists'
        ]);

        // Test get endpoint
        $response = $this->get('/cleaners/1/bookings');
        $response->seeStatusCode(200);
        $company = Company::find(1);

        $response->seeJson([
            'data' => [
                [
                    'id' => 1,
                    'hour' => [
                        'start' => '08:00',
                        'end' => '10:00',
                    ],
                    'date' => $now->format('Y-m-d'),
                    'company' => [
                        'id' => $company->id,
                        'name' => $company->name
                    ]
                ]        
            ]
        ]);
    }

    /**
     * Test booking delete
     * Exception case test
     *
     * @return void
     */
    public function testBookingDeleteEndpoint()
    {
        $response = $this->delete('/cleaners/1/bookings/1');
        $response->seeStatusCode(404);

        $tomorrow = Carbon::tomorrow();
        Booking::forceCreate([
            'cleaner_id' => 1,
            'company_id' => 1,
            'start' => '16:00',
            'end' => '20:00',
            'date' => $tomorrow->format('Y-m-d')
        ]);

        $response = $this->delete('/cleaners/1/bookings/1');
        $response->seeStatusCode(202);
    }

    /**
     * Test booking patch endpoint
     * Test will try to update the start time of booking
     *
     * @return void
     */
    public function testBookingPatchEndpoint()
    {
        $tomorrow = Carbon::tomorrow();
        if ($tomorrow->isFriday()) {
            $tomorrow->add(1, 'day');
        }

        Booking::forceCreate([
            'cleaner_id' => 1,
            'company_id' => 1,
            'start' => '16:00',
            'end' => '20:00',
            'date' => $tomorrow->format('Y-m-d')
        ]);

        // Invalid Interval 17:00 - 20:00 3 hours not valid
        $response = $this->patch('/cleaners/1/bookings/1', ['start' => '17:00']);
        $response->seeStatusCode(400);
        $response->seeJson([
            'error' => 'Cleaning interval must be 2 or 4 hours'
        ]);

        // Valid Interval
        $response = $this->patch('/cleaners/1/bookings/1', ['start' => '18:00']);
        $response->seeStatusCode(202);

        $booking = Booking::find(1);
        $this->assertTrue($booking->start->eq(Carbon::createFromFormat('H:i', '18:00')));
    }
}
