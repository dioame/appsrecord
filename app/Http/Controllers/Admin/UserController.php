<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $users = User::query()
            ->withCount([
                'appListings',
                'appListings as pending_apps_count' => fn ($query) => $query->pendingApproval(),
            ])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(24)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function toggleTrusted(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('status', 'Admin accounts are always trusted.');
        }

        $user->forceFill([
            'is_trusted' => ! $user->is_trusted,
        ])->save();

        $label = $user->is_trusted ? 'trusted' : 'not trusted';

        return back()->with('status', "{$user->name} is now {$label}.");
    }
}
