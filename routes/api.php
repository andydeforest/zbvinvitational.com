<?php

use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\DonorLogoController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\GolferController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderItemController;
use App\Http\Controllers\API\StatisticsController;
use App\Http\Controllers\API\StripeController;
use Illuminate\Support\Facades\Route;

Route::post('/payment-intent', [StripeController::class, 'create']);
Route::get('orders/{order}', [OrderController::class, 'show']);
Route::delete('orders/{order}', [OrderController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('contact', [ContactController::class, 'index']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('order-items', [OrderItemController::class, 'index']);
    // donors
    Route::post('/donor-logos', [DonorLogoController::class, 'store']);
    Route::delete('/donor-logos/{donorLogo}', [DonorLogoController::class, 'destroy']);
    Route::post('/donor-logos/bulk-delete', [DonorLogoController::class, 'bulkDestroy']);
    // gallery
    Route::post('gallery', [GalleryController::class, 'store']);
    Route::delete('gallery/{photo}', [GalleryController::class, 'destroy']);
    Route::post('gallery/bulk-delete', [GalleryController::class, 'bulkDestroy']);
    // golfers
    Route::get('/golfers', [GolferController::class, 'index']);
    // admin stats
    Route::get('/statistics', [StatisticsController::class, 'index']);

});
