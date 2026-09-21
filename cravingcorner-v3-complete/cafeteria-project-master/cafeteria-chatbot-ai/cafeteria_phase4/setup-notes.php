<?php
// 1) Add to routes/web.php:

use App\Http\Controllers\ChatbotController;

Route::middleware('auth')->group(function () {
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/respond', [ChatbotController::class, 'respond'])->name('chatbot.respond');
});


// 2) Add to config/services.php (same as the first project — reuse the
//    same GROQ_API_KEY in your .env, no need for a new one):

'groq' => [
    'api_key' => env('GROQ_API_KEY'),
    'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
    'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
],


// 3) Add to your .env (same key you used before works fine here too):
// GROQ_API_KEY=your_key_here
// GROQ_MODEL=llama-3.3-70b-versatile
// GROQ_BASE_URL=https://api.groq.com/openai/v1
