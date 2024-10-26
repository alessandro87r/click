<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RatingRestaurant;


class RestaurantRecommendationController extends Controller
{
    /**
     * Get top 5 resturant rating on $city
     * 
     * @param Request $request
     * @param String $city
     * 
     * @return json 
     */
    public function recommended(Request $request, $city)
    {
        $cityParam = strtolower($city);

        $ratingRestaurants = RatingRestaurant::with('user','restaurant')->where('city', $cityParam)->get();

        $restaurants = [];
        foreach ($ratingRestaurants as $ratingRestaurant) {
            $weight = $ratingRestaurant->user->verified ? 4 : 1;
            if(!array_key_exists($ratingRestaurant->restaurant_id, $restaurants)){              
                $restaurants[$ratingRestaurant->restaurant_id] = [ 
                    "ristorante_id" => $ratingRestaurant->restaurant_id,
                    "nome" => $ratingRestaurant->restaurant->name,
                    "rating" => $weight * $ratingRestaurant->rating
                ];
            }
            else{
                $restaurants[$ratingRestaurant->restaurant_id]["rating"] += $weight * $ratingRestaurant->rating;
            }
        }

        usort($restaurants, function ($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });

        $topFiveRestaurants = array_slice($restaurants, 0, 5);

        return response()->json($topFiveRestaurants);
    }
}
