<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CleanerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test cleaner both 2 endpoints
     * Create new cleaner and will retrieve
     * to see whether response is correct.
     * Check create cleaner exception case HTTP 422
     *
     * @return void
     */
    public function testCleanerEndpoints()
    {
        // Test empty case
        $response = $this->post('/cleaners', []);
        $response->seeStatusCode(422);
        $response->seeJson([
            'name' => [ 'The name field is required.']
        ]);

        // Test with correct body
        $response = $this->post('/cleaners', ['name' => 'Test']);
        $response->seeStatusCode(201);

        $response = $this->get('/cleaners');
        $response->seeStatusCode(200);
        $response->seeJson([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Test'
                ]
            ]
        ]);
    }
}
