<?php

namespace App\Providers;

use App\Models\AppListing;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::bind('app', function (string $value) {
            return AppListing::query()->findOrFail($value);
        });

        View::composer('layouts.public', function ($view) {
            $view->with(
                'navCategories',
                Category::query()->orderBy('sort_order')->orderBy('name')->get()
            );
        });
    }
}
