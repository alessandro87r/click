<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Database\Seeders\RestaurantsSeeder;
use Database\Seeders\RatingsRestaurantsSeeder;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RestaurantsSeeder::class,
            RatingsRestaurantsSeeder::class,
        ]);
    }
}
