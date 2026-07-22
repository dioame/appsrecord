<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AppsRecord') — {{ config('app.name', 'AppsRecord') }}</title>
    <meta name="description" content="@yield('meta_description', 'Discover and showcase apps by category — your portfolio app store.')">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="font-body antialiased text-[#1D1D1F]"
    :class="{ 'overflow-hidden': mobileNav || categoriesOpen }"
    x-data="{
    mobileNav: false,
    categoriesOpen: false,
    categoryQuery: '',
    openCategories() {
        this.categoriesOpen = true;
        this.categoryQuery = '';
        this.mobileNav = false;
        this.$nextTick(() => this.$refs.categorySearch?.focus());
    },
    closeCategories() { this.categoriesOpen = false; },
}" @keydown.escape.window="closeCategories()">
    <div class="store-shell">
        {{-- Mobile top bar --}}
        <div class="store-topbar lg:hidden">
            <button type="button" class="inline-flex cursor-pointer items-center justify-center rounded-lg p-2 text-[#1D1D1F] hover:bg-black/5" @click="mobileNav = !mobileNav" aria-label="Open menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-display text-[16px] font-semibold cursor-pointer">
                <x-application-logo class="h-7 w-7 rounded-[8px]" />
                <span>AppsRecord</span>
            </a>
            @auth
                <a href="{{ route('my-apps.create') }}" class="btn-primary !px-3 !py-1 text-[12px]">Submit</a>
            @else
                <a href="{{ route('login') }}" class="btn-primary !px-3 !py-1 text-[12px]">Sign In</a>
            @endauth
        </div>

        {{-- Overlay --}}
        <div x-show="mobileNav" x-cloak class="fixed inset-0 z-40 bg-black/30 lg:hidden" @click="mobileNav = false"></div>

        {{-- Sidebar --}}
        <aside
            class="store-sidebar transition-transform duration-200 ease-out"
            :class="mobileNav ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            aria-label="Store navigation"
        >
            <a href="{{ route('home') }}" class="mb-4 flex items-center gap-2.5 px-1 cursor-pointer" @click="mobileNav = false">
                <x-application-logo class="h-9 w-9 rounded-[10px]" />
                <span class="font-display text-[17px] font-semibold tracking-tight text-[#1D1D1F]">AppsRecord</span>
            </a>

            <form action="{{ route('search') }}" method="GET" class="mb-4">
                @if (request('author'))
                    <input type="hidden" name="author" value="{{ request('author') }}">
                @endif
                <label class="relative block">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-[#86868B]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
                    </span>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search apps or authors" class="store-search pl-9" autocomplete="off">
                </label>
            </form>

            <nav class="space-y-0.5" aria-label="Primary">
                <a href="{{ route('home') }}" class="side-link {{ request()->routeIs('home') && ! request()->routeIs('categories.*') ? 'side-link-active' : '' }}" @click="mobileNav = false">
                    <svg class="side-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                    Apps
                </a>
                <a href="{{ route('search') }}" class="side-link {{ request()->routeIs('search') ? 'side-link-active' : '' }}" @click="mobileNav = false">
                    <svg class="side-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
                    Search
                </a>
                <a href="{{ route('home') }}#featured" class="side-link" @click="mobileNav = false">
                    <svg class="side-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l2.4 6.2L21 12l-6.6 2.8L12 21l-2.4-6.2L3 12l6.6-2.8L12 3z"/></svg>
                    Featured
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="side-link" @click="mobileNav = false">
                        <svg class="side-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 17V9m4 8V7m4 10v-4"/></svg>
                        Library
                    </a>
                    <a href="{{ route('my-apps.create') }}" class="side-link" @click="mobileNav = false">
                        <svg class="side-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                        Submit
                    </a>
                @else
                    <a href="{{ route('login') }}" class="side-link" @click="mobileNav = false">
                        <svg class="side-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                        Sign In
                    </a>
                @endauth
                <a href="{{ route('docs') }}" class="side-link {{ request()->routeIs('docs') ? 'side-link-active' : '' }}" @click="mobileNav = false">
                    <svg class="side-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Docs
                </a>
            </nav>

            @php
                $allNavCategories = collect($navCategories ?? []);
                $previewLimit = 7;
                $activeCategorySlug = request()->routeIs('categories.show') ? request()->route('slug') : null;
                $previewCategories = $allNavCategories->take($previewLimit);
                if ($activeCategorySlug && ! $previewCategories->contains(fn ($c) => $c->slug === $activeCategorySlug)) {
                    $activeCategory = $allNavCategories->firstWhere('slug', $activeCategorySlug);
                    if ($activeCategory) {
                        $previewCategories = $previewCategories->take($previewLimit - 1)->prepend($activeCategory)->values();
                    }
                }
            @endphp

            <div class="mt-6 flex min-h-0 flex-1 flex-col overflow-hidden">
                <div class="mb-2 flex shrink-0 items-center justify-between px-2.5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Categories</p>
                    <button
                        type="button"
                        class="text-[12px] font-medium text-[#0071E3] cursor-pointer hover:opacity-70"
                        @click="openCategories()"
                    >All</button>
                </div>

                <div class="min-h-0 flex-1 space-y-0.5 overflow-y-auto overscroll-contain">
                    @foreach ($previewCategories as $navCategory)
                        <a href="{{ route('categories.show', $navCategory->slug) }}"
                           class="cat-link {{ $activeCategorySlug === $navCategory->slug ? 'cat-link-active' : '' }}"
                           @click="mobileNav = false">
                            <x-category-icon :slug="$navCategory->slug" />
                            <span class="min-w-0 flex-1 truncate">{{ $navCategory->name }}</span>
                            <span class="cat-count">{{ $navCategory->apps_count }}</span>
                        </a>
                    @endforeach
                </div>

                @if ($allNavCategories->count() > $previewLimit)
                    <button
                        type="button"
                        class="cat-more mt-1 shrink-0"
                        @click="openCategories()"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#E8E8ED] text-[#0071E3]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h14"/></svg>
                        </span>
                        <span class="truncate">Browse all {{ $allNavCategories->count() }}</span>
                    </button>
                @endif
            </div>

            <div class="mt-3 shrink-0 border-t border-[#D2D2D7] bg-[#F5F5F7] pt-3">
                <p class="px-2.5 text-[11px] leading-relaxed text-[#86868B]">&copy; {{ date('Y') }} AppsRecord</p>
            </div>
        </aside>

        {{-- Categories browser (no sidebar scroll) --}}
        <div
            x-show="categoriesOpen"
            x-cloak
            class="fixed inset-0 z-[70] flex items-end justify-center p-0 sm:items-center sm:p-6"
            role="dialog"
            aria-modal="true"
            aria-label="Browse categories"
        >
            <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="closeCategories()" x-transition.opacity></div>

            <div
                class="category-sheet relative z-10 flex max-h-[88vh] w-full max-w-2xl flex-col overflow-hidden rounded-t-[28px] bg-white shadow-2xl sm:rounded-[28px]"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-6 opacity-0 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="translate-y-4 opacity-0 sm:scale-95"
                @click.stop
            >
                <div class="flex items-center justify-between gap-3 border-b border-[#E8E8ED] px-5 py-4">
                    <div>
                        <h2 class="font-display text-[20px] font-bold text-[#1D1D1F]">Categories</h2>
                        <p class="text-[13px] text-[#86868B]">{{ $allNavCategories->count() }} ways to explore apps</p>
                    </div>
                    <button type="button" class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-[#F5F5F7] text-[#1D1D1F] hover:bg-[#E8E8ED]" @click="closeCategories()" aria-label="Close">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="border-b border-[#E8E8ED] px-5 py-3">
                    <label class="relative block">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-[#86868B]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
                        </span>
                        <input
                            type="search"
                            x-ref="categorySearch"
                            x-model="categoryQuery"
                            placeholder="Filter categories…"
                            class="store-search w-full pl-9"
                            autocomplete="off"
                        >
                    </label>
                </div>

                <div class="category-sheet-body grid grid-cols-1 gap-2 overflow-y-auto p-4 sm:grid-cols-2 sm:p-5">
                    @foreach ($allNavCategories as $navCategory)
                        <a
                            href="{{ route('categories.show', $navCategory->slug) }}"
                            class="category-tile {{ $activeCategorySlug === $navCategory->slug ? 'category-tile-active' : '' }}"
                            data-name="{{ \Illuminate\Support\Str::lower($navCategory->name) }}"
                            x-show="!categoryQuery || $el.dataset.name.includes(categoryQuery.toLowerCase().trim())"
                            @click="closeCategories()"
                        >
                            <span class="category-tile-icon">
                                <x-category-icon :slug="$navCategory->slug" class="h-5 w-5 text-[#0071E3]" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="flex items-center gap-2">
                                    <span class="block truncate text-[14px] font-medium text-[#1D1D1F]">{{ $navCategory->name }}</span>
                                    <span class="cat-count !bg-white">{{ $navCategory->apps_count }}</span>
                                </span>
                                <span class="mt-0.5 block truncate text-[12px] text-[#86868B]">{{ $navCategory->description }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Main --}}
        <div class="store-main">
            @if (session('status'))
                <div class="store-main-inner !pb-0 !pt-4">
                    <div class="rounded-xl bg-emerald-50 px-3 py-2 text-[13px] text-emerald-800" role="status">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
