<?php

namespace App\Http\Controllers;

use App\Models\AppListing;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $apps = AppListing::query()
            ->where('user_id', auth()->id())
            ->with('category')
            ->latest()
            ->get();

        return view('dashboard', compact('apps'));
    }
}
