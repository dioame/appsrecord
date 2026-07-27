<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'AI',
            'Offline',
            'Open Source',
            'Free',
            'Productivity',
            'Finance',
            'Education',
            'Health',
            'Social',
            'Utilities',
            'Maps',
            'Travel',
            'Food',
            'Photo',
            'Music',
            'Shopping',
            'News',
            'Design',
            'Developer',
            'Weather',
            'Mobile First',
            'Web App',
            'Desktop',
            'Collaboration',
            'Privacy',
            'Automation',
            'Analytics',
            'Realtime',
        ];

        foreach ($tags as $name) {
            Tag::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
