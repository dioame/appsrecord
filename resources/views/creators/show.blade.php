@extends('layouts.portfolio')

@section('title', $creator->name)
@section('meta_description', $creator->bio ?: 'Apps by '.$creator->name)

@section('content')
<div
    class="mx-auto w-full max-w-[980px] px-4 py-8 sm:px-6 sm:py-10"
    x-data="{ aboutOpen: false }"
    @keydown.escape.window="aboutOpen = false"
>
    <header class="mb-8 flex flex-col gap-5 sm:mb-10 sm:flex-row sm:items-center sm:gap-5">
        <div class="author-avatar !h-16 !w-16 !text-[18px] sm:!h-20 sm:!w-20 sm:!text-[20px]">
            @if ($creator->avatar)
                <img src="{{ $creator->avatar }}" alt="" class="h-full w-full object-cover">
            @else
                <span>{{ $creator->initials() }}</span>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">App portfolio</p>
            <h1 class="mt-1 font-display text-[28px] font-bold tracking-tight text-[#1D1D1F] sm:text-[34px]">{{ $creator->name }}</h1>
            @if ($creator->bio)
                <p class="mt-1.5 max-w-2xl text-[14px] text-[#86868B] sm:text-[15px]">{{ \Illuminate\Support\Str::limit($creator->bio, 140) }}</p>
            @endif
            <p class="mt-2 text-[13px] text-[#86868B]">
                {{ $apps->count() }} {{ \Illuminate\Support\Str::plural('app', $apps->count()) }}
                @if ($categories->isNotEmpty())
                    · {{ $categories->count() }} {{ \Illuminate\Support\Str::plural('category', $categories->count()) }}
                @endif
            </p>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="btn-secondary"
                    @click="aboutOpen = true"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    About author
                </button>
                @if ($creator->websiteUrl())
                    <a
                        href="{{ $creator->websiteUrl() }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-primary"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Website
                    </a>
                @endif
            </div>
        </div>
    </header>

    {{-- About author sheet --}}
    <div
        x-show="aboutOpen"
        x-cloak
        class="fixed inset-0 z-[70] flex items-end justify-center p-0 sm:items-center sm:p-6"
        role="dialog"
        aria-modal="true"
        aria-label="About {{ $creator->name }}"
    >
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]" @click="aboutOpen = false" x-transition.opacity></div>

        <div
            class="relative z-10 flex max-h-[88vh] w-full max-w-lg flex-col overflow-hidden rounded-t-[28px] bg-white shadow-2xl sm:rounded-[28px]"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-y-6 opacity-0 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="translate-y-4 opacity-0 sm:scale-95"
            @click.stop
        >
            <div class="flex items-start justify-between gap-3 border-b border-[#E8E8ED] px-5 py-4">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="author-avatar !h-12 !w-12 !text-[14px]">
                        @if ($creator->avatar)
                            <img src="{{ $creator->avatar }}" alt="" class="h-full w-full object-cover">
                        @else
                            <span>{{ $creator->initials() }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="truncate font-display text-[18px] font-bold text-[#1D1D1F]">{{ $creator->name }}</h2>
                        <p class="text-[13px] text-[#86868B]">Author</p>
                    </div>
                </div>
                <button type="button" class="inline-flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full bg-[#F5F5F7] text-[#1D1D1F] hover:bg-[#E8E8ED]" @click="aboutOpen = false" aria-label="Close">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto px-5 py-5">
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-[#F5F5F7] px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">Apps</p>
                        <p class="mt-1 text-[22px] font-semibold tabular-nums text-[#1D1D1F]">{{ $apps->count() }}</p>
                    </div>
                    <div class="rounded-2xl bg-[#F5F5F7] px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">Categories</p>
                        <p class="mt-1 text-[22px] font-semibold tabular-nums text-[#1D1D1F]">{{ $categories->count() }}</p>
                    </div>
                </div>

                <div class="mt-5">
                    <p class="text-[12px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">About</p>
                    @if ($creator->bio)
                        <p class="mt-2 text-[15px] leading-relaxed text-[#1D1D1F]">{{ $creator->bio }}</p>
                    @else
                        <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">{{ $creator->name }} hasn’t added a bio yet.</p>
                    @endif
                </div>

                @if ($creator->websiteUrl())
                    <div class="mt-5">
                        <p class="text-[12px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">Website</p>
                        <a
                            href="{{ $creator->websiteUrl() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-2 inline-flex items-center gap-2 text-[15px] font-medium text-[#0071E3] hover:underline"
                        >
                            {{ $creator->websiteHost() }}
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap gap-2 border-t border-[#E8E8ED] px-5 py-4">
                @if ($creator->websiteUrl())
                    <a href="{{ $creator->websiteUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-primary">Visit website</a>
                @endif
                <button type="button" class="btn-secondary" @click="aboutOpen = false">Close</button>
            </div>
        </div>
    </div>

    @if ($apps->isEmpty())
        <div class="rounded-2xl bg-white px-6 py-14 text-center text-[15px] text-[#86868B]">
            No published apps yet.
        </div>
    @else
        <div class="space-y-8">
            @foreach ($categories as $category)
                <section id="cat-{{ $category->slug ?? 'apps' }}">
                    <div class="mb-2 flex items-baseline justify-between gap-3">
                        <h2 class="section-title">{{ $category->name }}</h2>
                        <span class="text-[13px] text-[#86868B]">{{ $category->apps->count() }}</span>
                    </div>
                    <div class="rounded-[18px] bg-white px-2.5 sm:rounded-[22px] sm:px-4">
                        @foreach ($category->apps as $app)
                            <a href="{{ route('creators.app', [$creator->slug, $app->slug]) }}" class="app-row group">
                                <div class="app-icon h-12 w-12 sm:h-[60px] sm:w-[60px]">
                                    @if ($app->logoUrl())
                                        <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-[#E8E8ED] text-[#86868B]">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.5"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex min-w-0 items-center gap-2">
                                        <h3 class="truncate text-[14px] font-normal leading-tight text-[#1D1D1F] sm:text-[15px]">{{ $app->name }}</h3>
                                        <x-platform-badge :platform="$app->platform" />
                                    </div>
                                    <p class="mt-0.5 truncate text-[12px] leading-tight text-[#86868B] sm:text-[13px]">
                                        {{ \Illuminate\Support\Str::limit($app->description, 48) }}
                                    </p>
                                    <div class="mt-1">
                                        <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" />
                                    </div>
                                </div>
                                <span class="btn-get" tabindex="-1">View</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</div>
@endsection
