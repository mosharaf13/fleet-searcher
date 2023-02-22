<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchStatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearcherController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::get('keywords', [SearchStatController::class, 'keywords']);
    Route::get('search-stats', [SearchStatController::class, 'listStats']);
    Route::get('raw-response/{id}', [SearchStatController::class, 'rawResponse']);

    Route::post('keywords', [SearcherController::class, 'upload']);
});
