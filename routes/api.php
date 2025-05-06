<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\RoomController;


use App\Http\Controllers\SceneController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
  });
  
Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('/devices', [DevicesController::class, 'index'])->middleware('auth:api');
    Route::get('/devices/{device}', [DevicesController::class, 'show'])->middleware('auth:api');
    Route::post('/devices', [DevicesController::class, 'store'])->middleware('auth:api');
    Route::put('/devices/{device}', [DevicesController::class, 'update'])->middleware('auth:api');
    Route::delete('/devices/{device}', [DevicesController::class, 'destroy'])->middleware('auth:api');

    // Rooms CRUD
    Route::apiResource('rooms', RoomController::class)->middleware('auth:api');
});

Route::middleware('auth:sanctum')->group(function () {
    // Scene routes
    Route::apiResource('scenes', SceneController::class);
});



