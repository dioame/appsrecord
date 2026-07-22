<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppListingRequest;
use App\Http\Requests\UpdateAppListingRequest;
use App\Models\AppListing;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AppListingController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('apps.create', compact('categories'));
    }

    public function store(StoreAppListingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $slug = $this->uniqueSlug($data['name']);

        $logoPath = $request->file('logo')->store('apps/logos', 'public');

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach (array_slice($request->file('images'), 0, 3) as $image) {
                $imagePaths[] = $image->store('apps/screenshots', 'public');
            }
        }

        AppListing::create([
            'user_id' => $request->user()->id,
            'category_id' => $data['category_id'],
            'platform' => $data['platform'],
            'name' => $data['name'],
            'author' => $data['author'],
            'sub_authors' => $data['sub_authors'] ?? [],
            'slug' => $slug,
            'description' => $data['description'],
            'link' => $data['link'] ?? null,
            'logo' => $logoPath,
            'images' => $imagePaths,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'App published successfully.');
    }

    public function show(AppListing $app): RedirectResponse
    {
        return redirect()->route('apps.public', $app->slug);
    }

    public function edit(AppListing $app): View
    {
        $this->authorizeOwner($app);

        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('apps.edit', compact('app', 'categories'));
    }

    public function update(UpdateAppListingRequest $request, AppListing $app): RedirectResponse
    {
        $this->authorizeOwner($app);

        $data = $request->validated();

        if ($app->name !== $data['name']) {
            $app->slug = $this->uniqueSlug($data['name'], $app->id);
        }

        $app->category_id = $data['category_id'];
        $app->platform = $data['platform'];
        $app->name = $data['name'];
        $app->author = $data['author'];
        $app->sub_authors = $data['sub_authors'] ?? [];
        $app->description = $data['description'];
        $app->link = $data['link'] ?? null;
        $app->is_published = $request->boolean('is_published');

        if ($request->hasFile('logo')) {
            if ($app->logo) {
                Storage::disk('public')->delete($app->logo);
            }
            $app->logo = $request->file('logo')->store('apps/logos', 'public');
        }

        $images = $app->images ?? [];

        if ($request->filled('remove_images')) {
            foreach ($request->input('remove_images', []) as $path) {
                if (in_array($path, $images, true)) {
                    Storage::disk('public')->delete($path);
                    $images = array_values(array_filter($images, fn ($image) => $image !== $path));
                }
            }
        }

        if ($request->hasFile('images')) {
            $remainingSlots = max(0, 3 - count($images));
            foreach (array_slice($request->file('images'), 0, $remainingSlots) as $image) {
                $images[] = $image->store('apps/screenshots', 'public');
            }
        }

        $app->images = $images;
        $app->save();

        return redirect()
            ->route('dashboard')
            ->with('status', 'App updated successfully.');
    }

    public function destroy(AppListing $app): RedirectResponse
    {
        $this->authorizeOwner($app);

        $app->deleteFiles();
        $app->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'App deleted successfully.');
    }

    private function authorizeOwner(AppListing $app): void
    {
        abort_unless($app->user_id === auth()->id(), 403);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (
            AppListing::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
