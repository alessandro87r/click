<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\RatingRestaurant;

class RestaurantRecommendationTest extends TestCase
{
    /**
     * Test che verifica che l'API restituisca gli stessi risultati della query SQL diretta
     *
     * @return void
     */
    public function test_recommended_restaurants_match_direct_sql_query(): void
    {

        $ratingRestaurant = RatingRestaurant::select('city')->first();
        $city = strtolower($ratingRestaurant[0]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . config('app.api_token')
        ])->get('/api/{$city}/restaurants/recommended');

        $apiResults = $response->json();

        $sqlResults = DB::select("
            SELECT 
                SUM(a.rating + IFNULL(b.rating, 0)) AS total_rating, 
                a.restaurant_id as ristorante_id, 
                a.name as nome
            FROM (
                SELECT 
                    SUM(rating) * 4 AS rating, 
                    ratings_restaurants.restaurant_id, 
                    restaurants.name
                FROM ratings_restaurants
                LEFT JOIN users ON ratings_restaurants.user_id = users.id
                LEFT JOIN restaurants ON ratings_restaurants.restaurant_id = restaurants.id
                WHERE ratings_restaurants.city = '{$city}'
                AND users.verified = 1
                GROUP BY ratings_restaurants.restaurant_id
            ) AS a
            LEFT JOIN (
                SELECT 
                    SUM(rating) AS rating, 
                    ratings_restaurants.restaurant_id, 
                    restaurants.name
                FROM ratings_restaurants
                LEFT JOIN users ON ratings_restaurants.user_id = users.id
                LEFT JOIN restaurants ON ratings_restaurants.restaurant_id = restaurants.id
                WHERE ratings_restaurants.city = '{$city}'
                AND users.verified = 0
                GROUP BY ratings_restaurants.restaurant_id
            ) AS b ON a.restaurant_id = b.restaurant_id
            GROUP BY a.restaurant_id, a.name
            ORDER BY total_rating DESC
            LIMIT 5
        ");

        // Converti i risultati SQL in array per confronto più facile
        $sqlResults = array_map(function ($item) {
            return [
                'ristorante_id' => $item->ristorante_id,
                'nome' => $item->nome,
                'rating' => (float) $item->total_rating
            ];
        }, $sqlResults);

        // Verifica che il numero di risultati sia uguale
        $this->assertEquals(
            count($sqlResults),
            count($apiResults),
            'Il numero di ristoranti restituiti non corrisponde'
        );

        // Verifica che ogni risultato corrisponda
        foreach ($sqlResults as $index => $sqlResult) {
            $this->assertEquals(
                $sqlResult['ristorante_id'],
                $apiResults[$index]['ristorante_id'],
                "L'ID del ristorante alla posizione {$index} non corrisponde"
            );

            $this->assertEquals(
                $sqlResult['nome'],
                $apiResults[$index]['nome'],
                "Il nome del ristorante alla posizione {$index} non corrisponde"
            );

            $this->assertEquals(
                $sqlResult['rating'],
                $apiResults[$index]['rating'],
                "Il rating del ristorante alla posizione {$index} non corrisponde"
            );
        }
    }

    public function test_unauthenticated()
    {
        $response = $this->getJson('/api/{city}/restaurants/recommended');
        $response->assertStatus(401);
    }

}
