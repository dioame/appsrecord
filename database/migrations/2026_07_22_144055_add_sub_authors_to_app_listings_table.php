<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_listings', function (Blueprint $table) {
            $table->json('sub_authors')->nullable()->after('author');
        });
    }

    public function down(): void
    {
        Schema::table('app_listings', function (Blueprint $table) {
            $table->dropColumn('sub_authors');
        });
    }
};
