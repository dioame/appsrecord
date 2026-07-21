<?php

namespace App\Http\Controllers;

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

        return view('creators.show', compact('creator', 'apps'));
    }
}
