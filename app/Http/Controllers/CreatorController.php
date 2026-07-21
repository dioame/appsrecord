<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use App\Models\User;
use Illuminate\View\View;

class CreatorController extends Controller
{
    public function show(string $slug): View
    {
        $creator = User::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $apps = $creator->publishedApps()
            ->with(['category', 'user'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->get();

        $categories = $apps
            ->groupBy(fn (AppListing $app) => $app->category?->id ?? 0)
            ->map(function ($grouped) {
                $category = $grouped->first()->category;

                return (object) [
                    'name' => $category?->name ?? 'Apps',
                    'slug' => $category?->slug,
                    'apps' => $grouped->values(),
                ];
            })
            ->sortBy('name')
            ->values();

        return view('creators.show', compact('creator', 'apps', 'categories'));
    }

    public function app(string $slug, string $appSlug): View
    {
        $creator = User::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $app = $creator->publishedApps()
            ->where('slug', $appSlug)
            ->with(['category', 'user'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->firstOrFail();

        $userRating = $app->ratingFor(auth()->user());

        $related = $creator->publishedApps()
            ->where('id', '!=', $app->id)
            ->where('category_id', $app->category_id)
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->take(4)
            ->get();

        if ($related->isEmpty()) {
            $related = $creator->publishedApps()
                ->where('id', '!=', $app->id)
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->latest()
                ->take(4)
                ->get();
        }

        return view('creators.app', compact('creator', 'app', 'related', 'userRating'));
    }
}
