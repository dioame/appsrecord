<?php

namespace App\Providers;

use App\Models\AppListing;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Socialite\WamissoProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Socialite::extend('wamisso', fn ($app) => Socialite::buildProvider(
            WamissoProvider::class,
            $app['config']['services.wamisso'],
        ));

        Route::bind('app', function (string $value) {
            return AppListing::query()->findOrFail($value);
        });

        View::composer('layouts.public', function ($view) {
            $view->with(
                'navCategories',
                Category::query()
                    ->withCount(['publishedApps as apps_count'])
                    ->orderByDesc('apps_count')
                    ->orderBy('name')
                    ->get()
            );
        });
    }
}
