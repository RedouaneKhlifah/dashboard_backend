<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketController;
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


        // Api Resources  
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('partenaires', PartenaireController::class);
        Route::post('/products/{product}', [ProductController::class, 'update']);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('tickets', TicketController::class);

        // Order routes
        Route::apiResource('orders', OrderController::class);
        Route::post('/orders/send-order-email/{devi}', [OrderController::class, 'sendOrderToEmail']);

        Route::apiResource('factures', FactureController::class);
        Route::post('/factures/send-factures-email/{facture}', [FactureController::class, 'sendFactureToEmail']);




    });
});