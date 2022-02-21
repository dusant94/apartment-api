<?php

namespace Tests\Unit;

use App\Models\Apartment;
use App\Models\Category;
// use PHPUnit\Framework\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ApartmentTest extends TestCase
{
    use RefreshDatabase;
    /** @var Faker */
    private $faker;

    /** @var Category */
    private $category;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->category = Category::factory()->count(1)->create();
        $this->apartment = Apartment::factory()->count(1)->create();
    }

    /**
     *
     * @return void
     */
    public function test_create_apartment()
    {


        $response = $this->post(
            '/api/apartment',
            [
                'name' => $this->faker->firstName,
                'price' => $this->faker->randomNumber,
                'currency' => 'USD',
                'description' => 'asd asda sasd ',
                'properties' =>  '{"size":64,"balcony_size":7,"location":"Banja Luka"}',
                'category_id' => 1,
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'price',
                    'currency',
                    'description',
                    'properties',
                    'category_id',
                ]
            );
    }
    /**
     *
     * @return void
     */
    public function test_update_apartment()
    {
        $response = $this->put(
            '/api/apartment/1',
            [
                'name' => "updated",
                'price' => $this->faker->randomNumber,
                'currency' => 'USD',
                'description' => 'asd asda sasd sssss ',
                'properties' =>  '{"size":64,"balcony_size":7,"location":"Banja Luka"}',
                'category_id' => 1,
            ]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                true
            ]);
    }
}
