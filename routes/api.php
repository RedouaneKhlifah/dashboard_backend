<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'SetLocale'])->group(function () {
    // Public route for signing in
    Route::post('/sign-in', [AuthController::class, 'signIn']);

    // Protected route requiring JWT authentication
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::middleware('auth:api')->get('/verify-token', function (Request $request) {
        return response()->json(['message' => 'Token is valid']);
    });
});
