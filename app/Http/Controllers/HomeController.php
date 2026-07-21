<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

    public function search(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $author = trim((string) $request->query('author', ''));
        $platform = trim((string) $request->query('platform', ''));
        $categorySlug = trim((string) $request->query('category', ''));

        $validPlatforms = AppListing::PLATFORMS;
        if ($platform !== '' && ! in_array($platform, $validPlatforms, true)) {
            $platform = '';
        }

        $category = $categorySlug !== ''
            ? Category::query()->where('slug', $categorySlug)->first()
            : null;

        $hasFilters = $q !== '' || $author !== '' || $platform !== '' || $category !== null;

        $apps = AppListing::query()
            ->where('is_published', true)
            ->with(['category', 'user'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('author', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($author !== '', fn ($query) => $query->byAuthor($author))
            ->when($platform !== '', fn ($query) => $query->where('platform', $platform))
            ->when($category !== null, fn ($query) => $query->where('category_id', $category->id))
            ->latest()
            ->take(48)
            ->get();

        $authors = $this->publishedAuthors();
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('search', compact(
            'apps',
            'q',
            'author',
            'platform',
            'category',
            'categories',
            'authors',
            'hasFilters',
        ));
    }

    /**
     * @return Collection<int, string>
     */
    private function publishedAuthors(): Collection
    {
        return AppListing::query()
            ->where('is_published', true)
            ->with('user:id,name')
            ->get(['id', 'user_id', 'author'])
            ->map(fn (AppListing $app) => $app->authorName())
            ->filter()
            ->unique()
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }
}
