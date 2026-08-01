<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_listings', function (Blueprint $table) {
            $table->string('approval_status', 20)->default('approved')->after('is_published');
        });

        DB::table('app_listings')->update(['approval_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('app_listings', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
};
