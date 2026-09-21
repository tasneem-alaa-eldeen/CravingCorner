<?php
// Add this block to routes/web.php, alongside the admin block from phase 2.

use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;

Route::middleware('auth')->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/food/{foodItem}', [MenuController::class, 'showFood'])->name('menu.food.show');
    Route::get('/menu/beverages/{beverage}', [MenuController::class, 'showBeverage'])->name('menu.beverage.show');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/my-orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
