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
            ['name' => 'Government', 'description' => 'Civic services, public programs, and official tools.', 'sort_order' => 3],
            ['name' => 'Education', 'description' => 'Learning platforms and study tools.', 'sort_order' => 4],
            ['name' => 'Health & Fitness', 'description' => 'Wellness, workouts, and habits.', 'sort_order' => 5],
            ['name' => 'Finance', 'description' => 'Budgeting, payments, and tracking.', 'sort_order' => 6],
            ['name' => 'Entertainment', 'description' => 'Games, media, and creative fun.', 'sort_order' => 7],
            ['name' => 'Social', 'description' => 'Community and communication apps.', 'sort_order' => 8],
            ['name' => 'Utilities', 'description' => 'Everyday helpers and system tools.', 'sort_order' => 9],
            ['name' => 'Maps & Navigation', 'description' => 'Directions, transit, and location tools.', 'sort_order' => 10],
            ['name' => 'Travel', 'description' => 'Trips, bookings, and itineraries.', 'sort_order' => 11],
            ['name' => 'Food & Drink', 'description' => 'Recipes, delivery, and dining guides.', 'sort_order' => 12],
            ['name' => 'Photo & Video', 'description' => 'Capture, edit, and share media.', 'sort_order' => 13],
            ['name' => 'Music', 'description' => 'Streaming, practice, and audio tools.', 'sort_order' => 14],
            ['name' => 'Shopping', 'description' => 'Stores, deals, and wish lists.', 'sort_order' => 15],
            ['name' => 'News', 'description' => 'Headlines, briefs, and journalism.', 'sort_order' => 16],
            ['name' => 'Lifestyle', 'description' => 'Home, hobbies, and daily living.', 'sort_order' => 17],
            ['name' => 'Graphics & Design', 'description' => 'Design, illustration, and creative tools.', 'sort_order' => 18],
            ['name' => 'Developer Tools', 'description' => 'APIs, IDEs, and builder utilities.', 'sort_order' => 19],
            ['name' => 'Weather', 'description' => 'Forecasts, alerts, and climate data.', 'sort_order' => 20],
            ['name' => 'Others', 'description' => 'Apps that do not fit another category.', 'sort_order' => 21],
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
