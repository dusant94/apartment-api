<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Str::random(10),
            'price' => $this->faker->numberBetween(100, 1000),
            'currency' => 'EUR',
            'description' => "sad sd sd  aa",
            'properties' => json_decode('{"size": 243234,"balcony_size": 324,"location":"adsasd"}'),
            'category_id' => Category::factory(),
        ];
    }
}
