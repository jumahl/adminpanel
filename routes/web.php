<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    // ... otras rutas de autenticación ...
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/orders/add/{product}', [OrderController::class, 'addToCart'])->name('orders.addToCart');
        Route::delete('/orders/remove/{orderDetail}', [OrderController::class, 'removeFromCart'])->name('orders.removeFromCart');
        Route::post('/orders/checkout/{order}', [OrderController::class, 'checkout'])->name('orders.checkout');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'products' => Product::paginate(9) // 9 productos por página
    ]);
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';