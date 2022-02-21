<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Category::factory()
        ->has(Apartment::factory()
            ->count(100))
        ->count(10)
        ->create();
    }
}
