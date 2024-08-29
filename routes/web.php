<?php

use App\Http\Controllers\Billing\CheckoutController;
use App\Http\Controllers\Setup\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\Users\EnsureUserBelongsToOrganization;
use App\Models\Administration\ModuleCategory;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'moduleCategories' => ModuleCategory::with(['modules' => fn($query) => $query->active()])->get(),
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
    Route::post('/remove-item-from-cart', [CheckoutController::class, 'removeItemFromCart'])->name('cart.item.remove');
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
});


require __DIR__ . '/auth.php';