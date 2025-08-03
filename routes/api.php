<?php

use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('events', [EventController::class, 'index']);