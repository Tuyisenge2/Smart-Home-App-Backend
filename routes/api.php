<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SceneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FcmTokenController;


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

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('/scenes', [SceneController::class, 'index'])->middleware('auth:api');
    Route::get('/scenes/{scene}', [SceneController::class, 'show'])->middleware('auth:api');
    Route::post('/scenes', [SceneController::class, 'store'])->middleware('auth:api');
    Route::put('/scenes/{scene}', [SceneController::class, 'update'])->middleware('auth:api');
    Route::delete('/scenes/{scene}', [SceneController::class, 'destroy'])->middleware('auth:api');
});

Route::group([
    'middleware' => ['api','auth:api'], 
    'prefix' => 'users'
], function ($router) {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::post('/{id}/deactivate', [UserController::class, 'deactivate']);
});


Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'roles'
], function ($router) {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'fcm-tokens'
], function ($router) {
    Route::get('/', [FcmTokenController::class, 'index']);
    Route::post('/', [FcmTokenController::class, 'store']);
    Route::delete('/{token}', [FcmTokenController::class, 'destroy']);
});