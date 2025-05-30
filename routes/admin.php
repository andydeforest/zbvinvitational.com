<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', function () {
    return Inertia::render('Admin/Login');
})->name('login');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::put('settings', [SettingsController::class, 'store'])->name('settings.update');

    Route::resource('products', ProductController::class);
    Route::resource('categories', ProductCategoryController::class);

    // doesnt follow resource structure, manually map
    Route::get('donors', [DonorController::class, 'index'])->name('donors.index');
    Route::post('donors', [DonorController::class, 'store'])->name('donors.store');
    Route::put('donors', [DonorController::class, 'update'])->name('donors.update');

    Route::get('golfers', [GolferController::class, 'index'])->name('golfers.index');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('contact', [ContactController::class, 'show'])->name('contact.show');

});
