<?php

namespace Tests\Unit;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class RateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $apartment = Apartment::factory()->create();

        $response1 = $this->post(
            '/api/token',
            [
                'email' => 'test@email.com',
                'password' => '1234567',
            ]
        );
        $token = $response1['data']['token'];
        $response2 = $this->post(
            '/api/rate',
            [
                'rating' => 4,
                'apartment_id' => $apartment->id,
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );
        $response2->assertStatus(Response::HTTP_CREATED);
        $response3 = $this->post(
            '/api/rate',
            [
                'rating' => 2,
                'apartment_id' => $apartment->id,
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );
        $response3->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
