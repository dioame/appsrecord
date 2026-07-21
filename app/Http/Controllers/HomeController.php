<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with(['publishedApps' => fn ($query) => $query->latest()->with('user')])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $featured = AppListing::query()
            ->where('is_published', true)
            ->with(['category', 'user'])
            ->latest()
            ->take(6)
            ->get();

        $totalApps = AppListing::query()->where('is_published', true)->count();

        return view('home', compact('categories', 'featured', 'totalApps'));
    }

    public function show(string $slug): View
    {
        $app = AppListing::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with(['category', 'user'])
            ->firstOrFail();

        $related = AppListing::query()
            ->where('is_published', true)
            ->where('category_id', $app->category_id)
            ->where('id', '!=', $app->id)
            ->latest()
            ->take(4)
            ->get();

        return view('apps.show', compact('app', 'related'));
    }

    public function category(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $apps = $category->publishedApps()
            ->with('user')
            ->latest()
            ->paginate(12);

        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('categories.show', compact('category', 'apps', 'categories'));
    }

    public function search(\Illuminate\Http\Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $apps = AppListing::query()
            ->where('is_published', true)
            ->with(['category', 'user'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->take(30)
            ->get();

        return view('search', compact('apps', 'q'));
    }
}
