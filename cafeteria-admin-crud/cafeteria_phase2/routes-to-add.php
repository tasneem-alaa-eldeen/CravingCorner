<?php
// Add this block to routes/web.php (near your other authenticated routes).
// Requires: 'auth' middleware (from Breeze) + the 'admin' alias you already
// registered in bootstrap/app.php in the previous step.

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodItemController;
use App\Http\Controllers\Admin\BeverageController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('food-items', FoodItemController::class);
    Route::resource('beverages', BeverageController::class);
});
