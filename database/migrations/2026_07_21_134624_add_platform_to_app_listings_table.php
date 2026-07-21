<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_listings', function (Blueprint $table) {
            $table->string('platform', 20)->default('mobile')->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('app_listings', function (Blueprint $table) {
            $table->dropColumn('platform');
        });
    }
};
