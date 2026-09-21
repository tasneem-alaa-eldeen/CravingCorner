<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('itemable');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            // One review per user per item — resubmitting updates it instead.
            $table->unique(['user_id', 'itemable_id', 'itemable_type'], 'one_review_per_user_per_item');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
