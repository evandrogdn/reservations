<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\UserController;

// open routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// authenticated routes
Route::middleware('auth:sanctum')->group(function () {
  // user
  Route::get('/user', [UserController::class, 'show']);
  Route::post('/logout', [UserController::class, 'logout']);

  // tables
  Route::resource('/tables', TablesController::class);

  // reservations
  Route::resource('/reservations', ReservationController::class);
});         