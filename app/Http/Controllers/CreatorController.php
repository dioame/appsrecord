<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use App\Models\User;
use App\Support\CvTemplates;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function cvPreview(Request $request, string $slug): View
    {
        [$creator, $apps, $media, $template] = $this->cvPayload($request, $slug);

        return view('creators.cv.preview', compact('creator', 'apps', 'media', 'template'));
    }

    public function cvPdf(Request $request, string $slug): Response
    {
        [$creator, $apps, $media, $template] = $this->cvPayload($request, $slug);

        $filename = \Illuminate\Support\Str::slug($creator->name ?: $creator->slug).'-cv-'.$template.'.pdf';

        return Pdf::loadView('creators.cv-pdf', compact('creator', 'apps', 'media', 'template'))
            ->setPaper('a4')
            ->download($filename);
    }

    /**
     * @return array{0: User, 1: \Illuminate\Support\Collection<int, AppListing>, 2: array{avatar: ?string}, 3: string}
     */
    protected function cvPayload(Request $request, string $slug): array
    {
        $creator = User::query()
            ->where('slug', $slug)
            ->firstOrFail();

        if (! $creator->hasCvContent()) {
            throw new NotFoundHttpException('This creator has not published a CV yet.');
        }

        $apps = $creator->publishedApps()
            ->with('category')
            ->latest()
            ->get();

        $template = CvTemplates::resolve($request->query('template'));
        $media = CvTemplates::media($creator, $apps);

        return [$creator, $apps, $media, $template];
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
