<?php

use App\Http\Controllers\Billing\CheckoutController;
use App\Http\Controllers\Setup\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\Users\EnsureUserBelongsToOrganization;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Worksome\Exchange\Facades\Exchange;

Route::get('/', function () {
    $exchangeRates = Exchange::rates('USD', ['GBP', 'EUR', 'KES']);
    $baseCurrency = ['USD' => 1];
    return Inertia::render('Welcome', [
        'exchangeRates' => array_merge($baseCurrency, $exchangeRates->getRates())
    ]);
});

Route::middleware(['auth', 'verified', EnsureUserBelongsToOrganization::class])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(ModuleController::class)->group(function () {
        Route::get('/all-products', 'index')->name('products');
        Route::prefix('module')->group(function () {
            Route::post('/subscribe/{module}', 'subscribe')->name('module.subscribe');
            Route::post('/unsubscribe/{module}', 'unSubscribe')->name('module.unsubscribe');
        });
    });

    Route::get('/get-cart-items', [CheckoutController::class, 'getCartItems'])->name('cart.items');
    Route::post('/add-item-to-cart', [CheckoutController::class, 'addItemToCart'])->name('cart.item.add');
    Route::post('/remove-item-to-cart', [CheckoutController::class, 'removeItemFromCart'])->name('cart.item.remove');
});


require __DIR__ . '/auth.php';