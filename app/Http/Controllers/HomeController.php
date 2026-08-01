<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with(['publishedApps' => fn ($query) => $query
                ->latest()
                ->with(['user', 'tags'])
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $featured = AppListing::query()
            ->publiclyVisible()
            ->with(['category', 'user', 'tags'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->take(6)
            ->get();

        $totalApps = AppListing::query()->publiclyVisible()->count();

        $topAuthors = $this->topAuthors(12);
        $popularTags = $this->popularTags(16);

        return view('home', compact('categories', 'featured', 'totalApps', 'topAuthors', 'popularTags'));
    }

    public function docs(): View
    {
        return view('docs');
    }

    public function show(string $slug): View
    {
        $app = AppListing::query()
            ->where('slug', $slug)
            ->publiclyVisible()
            ->with(['category', 'user', 'tags'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->firstOrFail();

        $userRating = $app->ratingFor(auth()->user());

        $related = AppListing::query()
            ->publiclyVisible()
            ->where('category_id', $app->category_id)
            ->where('id', '!=', $app->id)
            ->with('tags')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->take(4)
            ->get();

        return view('apps.show', compact('app', 'related', 'userRating'));
    }

    public function category(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $apps = $category->publishedApps()
            ->with(['user', 'tags'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
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
        $tagSlugs = collect(explode(',', (string) $request->query('tags', '')))
            ->map(fn ($slug) => trim($slug))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $validPlatforms = AppListing::PLATFORMS;
        if ($platform !== '' && ! in_array($platform, $validPlatforms, true)) {
            $platform = '';
        }

        $category = $categorySlug !== ''
            ? Category::query()->where('slug', $categorySlug)->first()
            : null;

        $hasFilters = $q !== '' || $author !== '' || $platform !== '' || $category !== null || $tagSlugs !== [];

        $apps = AppListing::query()
            ->publiclyVisible()
            ->with(['category', 'user', 'tags'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('author', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('tags', fn ($tags) => $tags->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($author !== '', fn ($query) => $query->byAuthor($author))
            ->when($platform !== '', fn ($query) => $query->where('platform', $platform))
            ->when($category !== null, fn ($query) => $query->where('category_id', $category->id))
            ->when($tagSlugs !== [], fn ($query) => $query->withTagSlugs($tagSlugs))
            ->latest()
            ->take(48)
            ->get();

        $authors = $this->publishedAuthors();
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $popularTags = $this->popularTags(24);
        $activeTags = $tagSlugs !== []
            ? Tag::query()->whereIn('slug', $tagSlugs)->orderBy('name')->get()
            : collect();

        return view('search', compact(
            'apps',
            'q',
            'author',
            'platform',
            'category',
            'categories',
            'authors',
            'hasFilters',
            'tagSlugs',
            'popularTags',
            'activeTags',
        ));
    }

    /**
     * @return Collection<int, Tag>
     */
    private function popularTags(int $limit = 16): Collection
    {
        return Tag::query()
            ->withCount(['appListings' => fn ($query) => $query->publiclyVisible()])
            ->whereHas('appListings', fn ($query) => $query->publiclyVisible())
            ->orderByDesc('app_listings_count')
            ->orderBy('name')
            ->take($limit)
            ->get();
    }

    /**
     * @return Collection<int, string>
     */
    private function publishedAuthors(): Collection
    {
        return AppListing::query()
            ->publiclyVisible()
            ->with('user:id,name')
            ->get(['id', 'user_id', 'author'])
            ->map(fn (AppListing $app) => $app->authorName())
            ->filter()
            ->unique()
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    /**
     * @return Collection<int, object{name: string, apps_count: int, logo: ?string, initials: string}>
     */
    private function topAuthors(int $limit = 12): Collection
    {
        return AppListing::query()
            ->publiclyVisible()
            ->with('user:id,name,avatar,slug,is_trusted,role')
            ->latest()
            ->get()
            ->groupBy(fn (AppListing $app) => $app->authorName())
            ->map(function (Collection $apps, string $name) {
                $lead = $apps->first(fn (AppListing $app) => $app->logoUrl()) ?? $apps->first();
                $words = preg_split('/\s+/', trim($name)) ?: [];
                $initials = collect($words)
                    ->filter()
                    ->take(2)
                    ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
                    ->implode('');

                return (object) [
                    'name' => $name,
                    'slug' => $lead?->user?->slug,
                    'apps_count' => $apps->count(),
                    'logo' => $lead?->logoUrl(),
                    'avatar' => $lead?->user?->avatarUrl(),
                    'is_trusted' => (bool) ($lead?->user?->isTrusted()),
                    'initials' => $initials !== '' ? $initials : 'A',
                ];
            })
            ->sortByDesc('apps_count')
            ->take($limit)
            ->values();
    }
}
