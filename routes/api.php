<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantRecommendationController;

use App\Http\Middleware\CheckApiToken;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});


//Route::get('/{city}/restaurants/recommended', function () {})->middleware(App\Http\Middleware\CheckApiToken::class);


Route::middleware('api.token')->group(function () {
    Route::get('{city}/restaurants/recommended', [RestaurantRecommendationController::class, 'recommended']);
});