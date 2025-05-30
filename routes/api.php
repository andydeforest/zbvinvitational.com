<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::post('/payment-intent', [StripeController::class, 'create']);
Route::get('orders/{order}', [OrderController::class, 'show']);
Route::delete('orders/{order}', [OrderController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('contact', [ContactController::class, 'index']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('order-items', [OrderItemController::class, 'index']);
    Route::post('/donor-logos', [DonorLogoController::class, 'store']);
    Route::delete('/donor-logos/{donorLogo}', [DonorLogoController::class, 'destroy']);
    Route::post('/donor-logos/bulk-delete', [DonorLogoController::class, 'bulkDestroy']);
    // golfers
    Route::get('/golfers', [GolferController::class, 'index']);
});
