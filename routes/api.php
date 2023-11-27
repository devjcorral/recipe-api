<?php

use App\Http\Controllers\API\RecipeController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::controller(RecipeController::class)->group(function(){
        Route::get('recipes', 'index');
        Route::post('recipes/add', 'store');
        Route::post('recipes/{recipe}/edit', 'update');
        Route::post('recipes/{recipe}/delete', 'destroy');
        Route::get('recipe-types', 'getTypes');
        Route::get('recipes/{recipe}', 'show');
        Route::post('mark/featured/{recipe}', 'isFeatured');
    });
});
