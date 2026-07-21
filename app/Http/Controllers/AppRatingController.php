<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use App\Models\AppRating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppRatingController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $app = AppListing::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        AppRating::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'app_listing_id' => $app->id,
            ],
            ['rating' => $validated['rating']]
        );

        return back()->with('status', 'Thanks for your rating!');
    }
}
