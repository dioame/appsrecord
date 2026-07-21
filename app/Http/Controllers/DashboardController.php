<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $user->ensureSlug();

        $apps = AppListing::query()
            ->where('user_id', $user->id)
            ->with('category')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->get();

        $publicUrl = $user->publicUrl();

        return view('dashboard', compact('apps', 'publicUrl', 'user'));
    }
}
