<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PartenaireController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'SetLocale'])->group(function () {
    // Public route for signing in
    Route::post('/sign-in', [AuthController::class, 'signIn']);

    // Protected routes requiring JWT authentication
    Route::middleware('auth:api')->group(function () {
        // Get authenticated user
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Verify token
        Route::get('/verify-token', function (Request $request) {
            return response()->json(['message' => 'Token is valid']);
        });

        // Clients resource routes (protected by JWT auth)
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('partenaires', PartenaireController::class);

    });
});