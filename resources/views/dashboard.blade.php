<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Library</p>
                <h2 class="font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">My Apps</h2>
            </div>
            <a href="{{ route('my-apps.create') }}" class="inline-flex items-center gap-1 rounded-full bg-[#0071E3] px-3.5 py-1.5 text-[13px] font-semibold text-white hover:bg-[#0077ED]">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                Add
            </a>
        </div>
    </x-slot>

    <div class="bg-[#F5F5F7] py-6">
        <div class="mx-auto max-w-[980px] space-y-4 px-4 sm:px-5">
            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 px-3 py-2 text-[13px] text-emerald-800" role="status">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Compact portfolio share strip --}}
            <section
                class="rounded-2xl bg-white px-4 py-3.5 sm:px-5"
                x-data="{ copied: false }"
            >
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#F5F5F7] text-[#0071E3]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[14px] font-semibold text-[#1D1D1F]">Client portfolio</p>
                            <p class="mt-0.5 text-[12px] text-[#86868B]">Public page of your published apps</p>
                            <div class="mt-2 flex items-center gap-2 rounded-xl bg-[#F5F5F7] px-3 py-2">
                                <p class="min-w-0 flex-1 truncate font-mono text-[12px] text-[#1D1D1F]">{{ $publicUrl }}</p>
                                <button
                                    type="button"
                                    class="shrink-0 rounded-lg px-2 py-1 text-[12px] font-semibold text-[#0071E3] transition hover:bg-white"
                                    @click="navigator.clipboard?.writeText(@js($publicUrl)); copied = true; setTimeout(() => copied = false, 1600)"
                                >
                                    <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <a
                        href="{{ $publicUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex shrink-0 items-center justify-center gap-1.5 self-stretch rounded-xl border border-[#D2D2D7] bg-white px-4 py-2.5 text-[13px] font-semibold text-[#1D1D1F] transition hover:bg-[#F5F5F7] sm:self-center"
                    >
                        Preview
                        <svg class="h-3.5 w-3.5 text-[#86868B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </section>

            @if ($apps->isEmpty())
                <div class="rounded-2xl bg-white px-6 py-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#F5F5F7] text-[#86868B]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4"/><path stroke-linecap="round" d="M12 8v8M8 12h8"/></svg>
                    </div>
                    <h3 class="mt-4 font-display text-[18px] font-bold text-[#1D1D1F]">No apps yet</h3>
                    <p class="mx-auto mt-1.5 max-w-sm text-[14px] text-[#86868B]">Add a name, description, logo, and up to 3 screenshots.</p>
                    <a href="{{ route('my-apps.create') }}" class="mt-5 inline-flex items-center gap-1 rounded-full bg-[#0071E3] px-4 py-2 text-[13px] font-semibold text-white hover:bg-[#0077ED]">Create your first app</a>
                </div>
            @else
                <section class="overflow-hidden rounded-2xl bg-white">
                    <div class="flex items-center justify-between border-b border-[#F0F0F2] px-4 py-3 sm:px-5">
                        <h3 class="text-[14px] font-semibold text-[#1D1D1F]">
                            {{ $apps->count() }} {{ \Illuminate\Support\Str::plural('app', $apps->count()) }}
                        </h3>
                        <p class="text-[12px] text-[#86868B]">
                            {{ $apps->where('is_published', true)->count() }} published
                        </p>
                    </div>

                    <ul class="divide-y divide-[#F0F0F2]">
                        @foreach ($apps as $app)
                            <li class="px-4 py-3.5 sm:px-5">
                                <div class="flex items-center gap-3">
                                    <div class="app-icon h-12 w-12 sm:h-14 sm:w-14">
                                        @if ($app->logoUrl())
                                            <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-[#E8E8ED] text-[#86868B]">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.5"/></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                            <p class="truncate text-[15px] font-medium text-[#1D1D1F]">{{ $app->name }}</p>
                                            @if ($app->is_published)
                                                <span class="inline-flex items-center rounded-full bg-[#E8F8EE] px-2 py-0.5 text-[11px] font-semibold text-[#248A3D]">Published</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-[#FFF4E5] px-2 py-0.5 text-[11px] font-semibold text-[#C93400]">Draft</span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 truncate text-[12px] text-[#86868B]">
                                            {{ $app->platformLabel() }} · {{ $app->category->name }}
                                            @if ($app->ratingsCount() > 0)
                                                · {{ number_format($app->averageRating(), 1) }}★
                                            @endif
                                        </p>
                                    </div>

                                    <div class="hidden shrink-0 items-center gap-1 sm:flex">
                                        @if ($app->is_published)
                                            <a href="{{ route('apps.public', $app->slug) }}" class="rounded-full px-3 py-1.5 text-[13px] font-medium text-[#0071E3] hover:bg-[#F5F5F7]" target="_blank" rel="noopener">View</a>
                                        @endif
                                        <a href="{{ route('my-apps.edit', $app) }}" class="rounded-full px-3 py-1.5 text-[13px] font-medium text-[#1D1D1F] hover:bg-[#F5F5F7]">Edit</a>
                                        <form method="POST" action="{{ route('my-apps.destroy', $app) }}" onsubmit="return confirm('Delete this app?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full px-3 py-1.5 text-[13px] font-medium text-[#FF3B30] hover:bg-[#FFF2F1]">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Mobile actions --}}
                                <div class="mt-3 flex gap-2 sm:hidden">
                                    @if ($app->is_published)
                                        <a href="{{ route('apps.public', $app->slug) }}" class="flex-1 rounded-xl bg-[#F5F5F7] py-2 text-center text-[13px] font-semibold text-[#0071E3]" target="_blank" rel="noopener">View</a>
                                    @endif
                                    <a href="{{ route('my-apps.edit', $app) }}" class="flex-1 rounded-xl bg-[#F5F5F7] py-2 text-center text-[13px] font-semibold text-[#1D1D1F]">Edit</a>
                                    <form method="POST" action="{{ route('my-apps.destroy', $app) }}" class="flex-1" onsubmit="return confirm('Delete this app?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full rounded-xl bg-[#FFF2F1] py-2 text-center text-[13px] font-semibold text-[#FF3B30]">Delete</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
