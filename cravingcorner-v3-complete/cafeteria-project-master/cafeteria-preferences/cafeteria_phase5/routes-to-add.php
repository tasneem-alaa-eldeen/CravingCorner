<?php
// Add to routes/web.php

use App\Http\Controllers\Customer\PreferenceController;

Route::middleware('auth')->group(function () {
    Route::get('/preferences', [PreferenceController::class, 'edit'])->name('preferences.edit');
    Route::post('/preferences', [PreferenceController::class, 'update'])->name('preferences.update');
});
