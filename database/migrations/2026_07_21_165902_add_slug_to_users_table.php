<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->text('bio')->nullable()->after('avatar');
        });

        User::query()->orderBy('id')->each(function (User $user) {
            if (! filled($user->slug)) {
                $user->forceFill([
                    'slug' => User::generateUniqueSlug($user->name, $user->id),
                ])->saveQuietly();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['slug', 'bio']);
        });
    }
};
