<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'SetLocale'])->group(function () {
    // Public route for signing in (keeping POST for authentication)
    Route::put('/sign-in', [AuthController::class, 'signIn']);

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

        // Client routes
        Route::get('/clients', [ClientController::class, 'index']);
        Route::put('/clients', [ClientController::class, 'store']);
        Route::get('/clients/{client}', [ClientController::class, 'show']);
        Route::patch('/clients/{client}', [ClientController::class, 'update']);
        Route::delete('/clients/{client}', [ClientController::class, 'destroy']);

        // Partenaire routes
        Route::get('/partenaires', [PartenaireController::class, 'index']);
        Route::put('/partenaires', [PartenaireController::class, 'store']);
        Route::get('/partenaires/{partenaire}', [PartenaireController::class, 'show']);
        Route::patch('/partenaires/{partenaire}', [PartenaireController::class, 'update']);
        Route::delete('/partenaires/{partenaire}', [PartenaireController::class, 'destroy']);
        Route::get('/partenaires/{partenaire}/tickets', [PartenaireController::class, 'getTicketsWithSum']);

        // Product routes
        Route::get('/products', [ProductController::class, 'index']);
        Route::put('/products', [ProductController::class, 'store']);
        Route::get('/products/{product}', [ProductController::class, 'show']);
        Route::patch('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Ticket routes
        Route::get('/tickets', [TicketController::class, 'index']);
        Route::put('/tickets', [TicketController::class, 'store']);
        Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
        Route::patch('/tickets/{ticket}', [TicketController::class, 'update']);
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);

        // Order routes
        Route::get('/orders', [OrderController::class, 'index']);
        Route::put('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::patch('/orders/{order}', [OrderController::class, 'update']);
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
        Route::put('/orders/send-order-email/{order}', [OrderController::class, 'sendOrderToEmail']);
        Route::put('/orders/store-and-publish', [OrderController::class, 'storeAndPublish']);
        Route::patch('/orders/update-and-publish/{order}', [OrderController::class, 'updateAndPublish']);

        // Facture routes
        Route::get('/factures', [FactureController::class, 'index']);
        Route::put('/factures', [FactureController::class, 'store']);
        Route::get('/factures/{facture}', [FactureController::class, 'show']);
        Route::patch('/factures/{facture}', [FactureController::class, 'update']);
        Route::delete('/factures/{facture}', [FactureController::class, 'destroy']);
        Route::put('/factures/send-factures-email/{facture}', [FactureController::class, 'sendFactureToEmail']);

        // Employee routes
        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::put('/employees', [EmployeeController::class, 'store']);
        Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
        Route::patch('/employees/{employee}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
        Route::put('/employees/history-of-pay/{employee}', [EmployeeController::class, 'StoreHistoryOfPay']);
        Route::get('/employees/history-of-pay/{employee}', [EmployeeController::class, 'getEmployeeHistoryOfPay']);

        // Transaction routes
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::put('/transactions', [TransactionController::class, 'store']);
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
        Route::patch('/transactions/{transaction}', [TransactionController::class, 'update']);
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);

        // Dashboard routes
        Route::get('/dashboard/{start_date?}/{end_date?}', [DashboardController::class, 'index']);
    });
});