<?php

use App\Http\Controllers\Admin\Setup\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureBelongsToOrganization;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::middleware(['auth', 'verified', EnsureBelongsToOrganization::class])->group(function () {

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
});


require __DIR__ . '/auth.php';