<?php

namespace Database\Seeders;



use App\Models\RatingRestaurant;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingsRestaurantsSeeder extends Seeder
{
    public function run()
    {
        $cities = [ 'milano', 'roma', 'torino', 'firenze'];
        $ratings = [2, 1, 0, -1, -2];

        Restaurant::all()->each(function ($restaurant) use ($cities, $ratings) {
            User::all()->each(function ($user) use ($restaurant, $cities, $ratings) {
                RatingRestaurant::create([
                    'restaurant_id' => $restaurant->id,
                    'user_id' => $user->id,
                    'rating' => $ratings[array_rand($ratings)],
                    'city' => $cities[array_rand($cities)],
                ]);
            });
        });
    }
}
