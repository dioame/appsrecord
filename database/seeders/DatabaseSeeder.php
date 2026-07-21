<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            AppListingSeeder::class,
        ]);

        User::query()->firstOrCreate(
            ['email' => 'demo@appsrecord.test'],
            [
                'name' => 'Demo Publisher',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}
