<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('headline', 160)->nullable()->after('website');
            $table->string('location', 120)->nullable()->after('headline');
            $table->json('skills')->nullable()->after('location');
            $table->json('experience')->nullable()->after('skills');
            $table->json('education')->nullable()->after('experience');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['headline', 'location', 'skills', 'experience', 'education']);
        });
    }
};
