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
        $this->category = Category::factory()->create();
        $this->apartment = Apartment::factory()->create();
    }
    /**
     *
     * @return void
     */
    public function test_index_apartment()
    {
        $apartments = Apartment::factory()->count(8)->create();
        $response = $this->get('/api/apartment/');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure(['data', 'meta', 'links']);

        // Test simple sorting by price
        $response2 = $this->get('/api/apartment?sort=price:asc');
        $response2->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure(['data', 'meta', 'links']);
        $previous = 0;
        foreach ($response2['data'] as $data) {
            $this->assertEquals($data['price'] >= $previous, true);
            $previous = $data['price'];
        }
        // Test converting price ( currency seeded in database is 'EUR' )
        $response2 = $this->get(
            '/api/apartment?sort=price:asc',
            ['CURRENCY' => 'BAM']
        );
        $response2->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(9, 'data')
            ->assertJsonStructure(['data', 'meta', 'links']);

        foreach ($response2['data'] as $data) {
            $this->assertEquals($data['currency'] == 'BAM', true);
        }
    }
    /**
     *
     * @return void
     */
    public function test_create_apartment()
    {
        $name = $this->faker->firstName;

        $response = $this->post(
            '/api/apartment',
            [
                'name' => $name,
                'price' => $this->faker->randomNumber,
                'currency' => 'USD',
                'description' => 'asd asda sasd ',
                'properties' =>  '{"size":64,"balcony_size":7,"location":"Banja Luka"}',
                'category_id' => $this->category->id,
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
            )->assertJsonPath('name', $name);
    }
    /**
     *
     * @return void
     */
    public function test_update_apartment()
    {
        $response = $this->put(
            '/api/apartment/' . $this->apartment->id,
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
            ->assertExactJson([true]);

        $this->assertDatabaseHas('apartments', [
            'id' => $this->apartment->id,
            'name' => "updated",
        ]);
    }
    /**
     *
     * @return void
     */
    public function test_delete_apartment()
    {
        $this->assertDatabaseHas('apartments', [
            'id' => $this->apartment->id,
            'deleted_at' => null,
        ]);

        $response = $this->delete(
            '/api/apartment/' . $this->apartment->id
        );
        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                true
            ]);

        $apartment = Apartment::withTrashed()->find($this->apartment->id);
        $this->assertEquals($apartment['deleted_at'] != null, true);
    }
}
