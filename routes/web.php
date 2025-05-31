<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', [PagesController::class, 'home'])->name('home');
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/gallery', [PagesController::class, 'gallery'])->name('gallery');
Route::get('/donors', [PagesController::class, 'donors'])->name('donors');
Route::get('/faq', [PagesController::class, 'faq'])->name('faq');

Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::get('confirmation/{order}', [ShopController::class, 'confirmation'])->name('confirmation');
});

Route::prefix('contact')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('contact');
    Route::post('/', [ContactController::class, 'store']);
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->withoutMiddleware([VerifyCsrfToken::class]);
