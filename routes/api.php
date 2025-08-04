<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// -> ROTTE PUBBLICHE
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('events', [EventController::class, 'index']);

// -> ROTTE PROTETTE
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
    return $request->user();
    });

    // dopo ricorda di aggiungere la rotta di logout
    // Route::post('/logout', [AuthController::class, 'logout']);

    // cart
    Route::get('cart', [CartItemsController::class, 'index']);
    Route::post('cart/add', [CartItemsController::class, 'add']);
    Route::post('cart/remove', [CartItemsController::class, 'remove']);

});