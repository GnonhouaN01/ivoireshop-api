<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Api\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->middleware('throttle:auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
    });

    Route::get('products', [Admin\ProductController::class, 'index']);
    Route::get('products/{slug}', [Admin\ProductController::class, 'show']);
    Route::get('categories', [Admin\CategoryController::class, 'index']);
    Route::get('categories/{slug}/products', [Admin\CategoryController::class, 'products']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',      [AuthController::class, 'me']);

        Route::prefix('cart')->group(function () {
            Route::get('/',             [Client\CartController::class, 'index']);
            Route::post('add',          [Client\CartController::class, 'add']);
            Route::put('items/{id}',    [Client\CartController::class, 'update']);
            Route::delete('items/{id}', [Client\CartController::class, 'remove']);
            Route::delete('clear',      [Client\CartController::class, 'clear']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('/',             [Client\OrderController::class, 'index']);
            Route::get('{orderNumber}', [Client\OrderController::class, 'show']);
            Route::post('checkout',     [Client\CheckoutController::class, 'checkout'])->middleware('throttle:checkout');
        });

        Route::middleware('is_admin')->prefix('admin')->group(function () {
            Route::get('dashboard', [Admin\DashboardController::class, 'index']);

            Route::apiResource('products',   Admin\ProductController::class)
                ->except(['index', 'show']);

            Route::apiResource('categories', Admin\CategoryController::class)
                ->except(['index']);

            Route::apiResource('orders',     Admin\OrderController::class)
                ->only(['index', 'show', 'update']);

            Route::get('customers', [Admin\DashboardController::class, 'customers']);
        });
    });
});

Route::post('/payment/notify', [PaymentController::class, 'notify'])->name('payment.notify')->middleware('throttle:checkout');
Route::get('/payment/return', [PaymentController::class, 'return'])->name('payment.return');
