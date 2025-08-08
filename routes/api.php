<?php

use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// -> ROTTE PUBBLICHE
Route::post('/login', [Api\AuthController::class, 'login']);
Route::post('/register', [Api\AuthController::class, 'register']);
Route::get('events', [Api\EventController::class, 'index']);

// -> ROTTE PROTETTE
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
    return $request->user();
    });

    // dopo ricorda di aggiungere la rotta di logout
    // Route::post('/logout', [AuthController::class, 'logout']);

    // cart
    Route::get('cart', [Api\CartItemsController::class, 'index']);
    Route::post('cart/add', [Api\CartItemsController::class, 'add']);
    Route::post('cart/remove', [Api\CartItemsController::class, 'remove']);
    Route::get('cart/summary', [Api\CheckoutController::class, 'summary']);
    Route::post('cart/address', [Api\AddressController::class, 'store']);

});