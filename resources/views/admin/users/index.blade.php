<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Admin</p>
                <h2 class="font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">Users</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.apps.index') }}" class="rounded-full px-3.5 py-1.5 text-[13px] font-semibold text-[#1D1D1F] hover:bg-black/5">Pending apps</a>
            </div>
        </div>
    </x-slot>

    <div class="bg-[#F5F5F7] py-6">
        <div class="mx-auto max-w-[980px] space-y-4 px-4 sm:px-5">
            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 px-3 py-2 text-[13px] text-emerald-800" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="search" name="q" value="{{ $q }}" placeholder="Search name or email" class="form-input flex-1" autocomplete="off">
                <button type="submit" class="rounded-full bg-[#0071E3] px-4 py-2 text-[13px] font-semibold text-white hover:bg-[#0077ED]">Search</button>
            </form>

            <section class="overflow-hidden rounded-2xl bg-white">
                <div class="border-b border-[#F0F0F2] px-4 py-3 sm:px-5">
                    <h3 class="text-[14px] font-semibold text-[#1D1D1F]">
                        {{ $users->total() }} {{ \Illuminate\Support\Str::plural('user', $users->total()) }}
                    </h3>
                    <p class="mt-0.5 text-[12px] text-[#86868B]">Trusted developers can publish apps without approval.</p>
                </div>

                <ul class="divide-y divide-[#F0F0F2]">
                    @forelse ($users as $user)
                        <li class="px-4 py-3.5 sm:px-5">
                            <div class="flex flex-wrap items-center gap-3">
                                <x-developer-avatar :user="$user" size="lg" />

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="truncate text-[15px] font-medium text-[#1D1D1F]">{{ $user->name }}</p>
                                        @if ($user->isAdmin())
                                            <span class="inline-flex items-center rounded-full bg-[#F2E8FF] px-2 py-0.5 text-[11px] font-semibold text-[#5E5CE6]">Admin</span>
                                        @endif
                                        <x-trusted-badge :user="$user" />
                                    </div>
                                    <p class="mt-0.5 truncate text-[12px] text-[#86868B]">
                                        {{ $user->email }}
                                        · {{ $user->app_listings_count }} {{ \Illuminate\Support\Str::plural('app', $user->app_listings_count) }}
                                        @if ($user->pending_apps_count > 0)
                                            · {{ $user->pending_apps_count }} pending
                                        @endif
                                    </p>
                                </div>

                                @unless ($user->isAdmin())
                                    <form method="POST" action="{{ route('admin.users.trusted', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-full px-3.5 py-1.5 text-[13px] font-semibold {{ $user->is_trusted ? 'bg-[#FFF2F1] text-[#FF3B30] hover:bg-[#FFE5E3]' : 'bg-[#E8F1FF] text-[#0071E3] hover:bg-[#D6E8FF]' }}">
                                            {{ $user->is_trusted ? 'Remove trusted' : 'Make trusted' }}
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-10 text-center text-[14px] text-[#86868B] sm:px-5">No users found.</li>
                    @endforelse
                </ul>

                @if ($users->hasPages())
                    <div class="border-t border-[#F0F0F2] px-4 py-3 sm:px-5">
                        {{ $users->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
