<?php
// 1) Add inside the admin group (Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group...):

use App\Http\Controllers\Admin\StatisticsController;

Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');


// 2) Add inside the customer auth group (with menu/cart/orders/favorites):

use App\Http\Controllers\Customer\SurpriseController;

Route::get('/surprise-me', SurpriseController::class)->name('surprise-me');
