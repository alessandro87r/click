<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RestaurantRecommendationController;

Route::middleware(['auth:api', 'verify.api.token'])->group(function () {
    Route::get('/{citta}/restaurants/recommended', [RestaurantRecommendationController::class, 'recommended']);
});