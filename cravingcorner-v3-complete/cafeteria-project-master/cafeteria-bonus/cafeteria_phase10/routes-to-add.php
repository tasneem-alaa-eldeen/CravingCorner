<?php
// Add to routes/web.php, inside the customer auth group:

use App\Http\Controllers\Customer\ReviewController;

Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
