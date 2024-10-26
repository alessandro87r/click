<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantRecommendationTest extends TestCase
{
    public function test_it_calculates_correct_recommendations()
    {
        // Crea ristoranti, utenti e valutazioni simulate
        // Effettua una richiesta di test autenticata e verifica il contenuto
    }

    public function test_unauthenticated()
    {
        $response = $this->getJson('/api/somecity/restaurants/recommended');
        $response->assertStatus(401);
    }

}
