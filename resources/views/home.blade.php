@extends('layouts.public')

@section('title', 'Apps')
@section('meta_description', 'Browse apps by category — App Store style catalog.')

@section('content')
<div class="store-main-inner pb-12">
    <header class="mb-6 flex items-end justify-between gap-3">
        <div>
            <h1 class="font-display text-[34px] font-bold leading-none tracking-tight text-[#1D1D1F]">Apps</h1>
            <p class="mt-2 text-[15px] text-[#86868B]">{{ $totalApps }} apps across {{ $categories->count() }} categories</p>
        </div>
        @auth
            <a href="{{ route('my-apps.create') }}" class="btn-get !normal-case !tracking-normal">+ Add</a>
        @else
            <a href="{{ route('register') }}" class="btn-get !normal-case !tracking-normal">Publish</a>
        @endauth
    </header>

    {{-- Lead with a featured hero if we have apps --}}
    @if ($featured->isNotEmpty())
        @php $lead = $featured->first(); @endphp
        <a href="{{ route('apps.public', $lead->slug) }}" class="hero-banner mb-8 block cursor-pointer transition hover:opacity-95">
            <div class="relative flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:gap-8 sm:p-8">
                <div class="app-icon h-[100px] w-[100px] ring-1 ring-white/20 sm:h-[120px] sm:w-[120px]">
                    @if ($lead->logoUrl())
                        <img src="{{ $lead->logoUrl() }}" alt="" class="h-full w-full object-cover">
                    @endif
                </div>
                <div class="min-w-0 flex-1 text-white">
                    <p class="text-[12px] font-semibold uppercase tracking-[0.1em] text-white/70">Featured</p>
                    <h2 class="mt-1 font-display text-[30px] font-bold leading-none sm:text-[36px]">{{ $lead->name }}</h2>
                    <p class="mt-2 max-w-xl text-[15px] text-white/85">{{ \Illuminate\Support\Str::limit($lead->description, 90) }}</p>
                    <p class="mt-2 text-[13px] text-white/60">Free · {{ $lead->category->name }}</p>
                </div>
            </div>
        </a>
    @endif

    <div id="apps" class="space-y-8">
        @foreach ($categories as $category)
            @if ($category->publishedApps->isNotEmpty())
                <section id="cat-{{ $category->slug }}">
                    <div class="mb-2 flex items-baseline justify-between gap-3">
                        <h2 class="section-title">{{ $category->name }}</h2>
                        <a href="{{ route('categories.show', $category->slug) }}" class="see-all">See All</a>
                    </div>

                    <div class="rounded-[22px] bg-[#F5F5F7] px-3 sm:px-4">
                        @php
                            $shelfApps = $category->publishedApps->take(6);
                            $columns = $shelfApps->chunk(ceil(max($shelfApps->count(), 1) / 3));
                        @endphp
                        <div class="grid sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3 lg:gap-x-8">
                            @foreach ($columns as $columnApps)
                                <div>
                                    @foreach ($columnApps as $app)
                                        @include('partials.app-row', ['app' => $app])
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    </div>

    @if ($featured->isNotEmpty())
        <section id="featured" class="mt-10">
            <div class="mb-3 flex items-baseline justify-between gap-3">
                <h2 class="section-title">New & Noteworthy</h2>
            </div>
            <div class="store-shelf">
                @foreach ($featured as $app)
                    @include('partials.app-card', ['app' => $app])
                @endforeach
            </div>
        </section>
    @endif

    @if ($totalApps === 0)
        <section class="rounded-[28px] bg-[#F5F5F7] px-6 py-16 text-center">
            <h2 class="section-title">No apps yet</h2>
            <p class="mx-auto mt-2 max-w-sm text-[15px] text-[#86868B]">Be the first to publish. Add a logo and up to three screenshots.</p>
            <a href="{{ route('register') }}" class="btn-primary mt-5">Get Started</a>
        </section>
    @endif
</div>
@endsection
