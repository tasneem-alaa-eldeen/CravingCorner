<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodItemController;
use App\Http\Controllers\Admin\BeverageController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Customer\PreferenceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Customer\SurpriseController;
use App\Http\Controllers\Customer\RecommendationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('food-items', FoodItemController::class);
    Route::resource('beverages', BeverageController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/food/{foodItem}', [MenuController::class, 'showFood'])->name('menu.food.show');
    Route::get('/menu/beverages/{beverage}', [MenuController::class, 'showBeverage'])->name('menu.beverage.show');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/my-orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // AI recommendations ("For You") page with match % per item.
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/respond', [ChatbotController::class, 'respond'])->name('chatbot.respond');
});

Route::middleware('auth')->group(function () {
    Route::get('/preferences', [PreferenceController::class, 'edit'])->name('preferences.edit');
    Route::post('/preferences', [PreferenceController::class, 'update'])->name('preferences.update');
});

Route::get('/surprise-me', SurpriseController::class)->name('surprise-me');
