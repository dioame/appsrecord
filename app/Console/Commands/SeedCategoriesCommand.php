<?php

namespace App\Console\Commands;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Console\Command;

class SeedCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed or refresh app store categories';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Seeding categories...');

        $this->callSilent(CategorySeeder::class);

        $count = Category::query()->count();

        $this->components->info("Categories ready ({$count} total).");

        $this->table(
            ['Name', 'Slug', 'Sort'],
            Category::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['name', 'slug', 'sort_order'])
                ->map(fn (Category $category) => [
                    $category->name,
                    $category->slug,
                    $category->sort_order,
                ])
                ->all()
        );

        return self::SUCCESS;
    }
}
