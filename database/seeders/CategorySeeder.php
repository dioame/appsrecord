<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Productivity', 'description' => 'Tools that help you get more done.', 'sort_order' => 1],
            ['name' => 'Business', 'description' => 'Apps built for teams and growth.', 'sort_order' => 2],
            ['name' => 'Education', 'description' => 'Learning platforms and study tools.', 'sort_order' => 3],
            ['name' => 'Health & Fitness', 'description' => 'Wellness, workouts, and habits.', 'sort_order' => 4],
            ['name' => 'Finance', 'description' => 'Budgeting, payments, and tracking.', 'sort_order' => 5],
            ['name' => 'Entertainment', 'description' => 'Games, media, and creative fun.', 'sort_order' => 6],
            ['name' => 'Social', 'description' => 'Community and communication apps.', 'sort_order' => 7],
            ['name' => 'Utilities', 'description' => 'Everyday helpers and system tools.', 'sort_order' => 8],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $category['sort_order'],
                ]
            );
        }
    }
}
