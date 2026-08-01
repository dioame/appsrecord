<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppApprovalController extends Controller
{
    public function index(): View
    {
        $apps = AppListing::query()
            ->pendingApproval()
            ->with(['user', 'category'])
            ->latest()
            ->paginate(24);

        return view('admin.apps.index', compact('apps'));
    }

    public function approve(AppListing $app): RedirectResponse
    {
        $app->forceFill([
            'is_published' => true,
            'approval_status' => AppListing::APPROVAL_APPROVED,
        ])->save();

        return back()->with('status', "{$app->name} approved and published.");
    }

    public function reject(AppListing $app): RedirectResponse
    {
        $app->forceFill([
            'is_published' => false,
            'approval_status' => AppListing::APPROVAL_REJECTED,
        ])->save();

        return back()->with('status', "{$app->name} rejected.");
    }
}
