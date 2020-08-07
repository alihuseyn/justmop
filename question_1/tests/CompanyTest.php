<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test company both 2 endpoints
     * Create new company and will retrieve
     * to see whether response is correct.
     * Check create company exception case HTTP 422
     *
     * @return void
     */
    public function testCompanyEndpoints()
    {
        // Test empty case
        $response = $this->post('/companies', []);
        $response->seeStatusCode(422);
        $response->seeJson([
            'name' => [ 'The name field is required.']
        ]);

        // Test with correct body
        $response = $this->post('/companies', ['name' => 'Test']);

        $response->seeStatusCode(201);

        $response = $this->get('/companies');
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
