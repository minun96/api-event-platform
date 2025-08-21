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
    Route::prefix('cart')->group(function() {
        Route::get('/', [Api\CartItemsController::class, 'index']);
        Route::post('/add', [Api\CartItemsController::class, 'add']);
        Route::post('/remove', [Api\CartItemsController::class, 'remove']);
    });

    // checkout
    Route::prefix('checkout')->group(function() {
        Route::get('/summary', [Api\CheckoutController::class, 'summary']);
        Route::post('/address', [Api\AddressController::class, 'store']);
        Route::post('/payment', [Api\PaymentController::class, 'payment']);
        Route::post('/confirm', [Api\ConfirmController::class, 'confirm']);
    });

});