<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\PlateController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecommendationController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    Route::get('/profile',  [ProfileController::class, 'show']);
    Route::put('/profile',  [ProfileController::class, 'update']);

    // Categories
    Route::apiResource('categories', CategoryController::class);
    Route::get('categories/{category}/plates', [CategoryController::class, 'plates']);

    // Plates
    Route::apiResource('plates', PlateController::class);

    // Ingredients
    Route::apiResource('ingredients', IngredientController::class)->except(['show']);

    // Recommendations
    Route::post('/recommendations/analyze/{plate}',  [RecommendationController::class, 'analyze']);
    Route::get('/recommendations',                   [RecommendationController::class, 'index']);
    Route::get('/recommendations/{plate}',           [RecommendationController::class, 'show']);

    // Admin
    Route::get('/admin/stats', [AdminController::class, 'stats']);
});
