<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('app_listing_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1–5
            $table->timestamps();

            $table->unique(['user_id', 'app_listing_id']);
            $table->index(['app_listing_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_ratings');
    }
};
