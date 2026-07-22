@extends('layouts.public')

@section('title', $q ? 'Search: '.$q : ($author ? 'Apps by '.$author : 'Search'))

@section('content')
<section class="store-main-inner pb-12">
    <header class="mb-5 sm:mb-6">
        <h1 class="font-display text-[28px] font-bold tracking-tight text-[#1D1D1F] sm:text-[34px]">Search</h1>
        <p class="mt-2 text-[13px] text-[#86868B] sm:text-[15px]">
            Find apps by name, description, or author — then narrow with filters.
        </p>
    </header>

    <form action="{{ route('search') }}" method="GET" class="mb-6 space-y-4">
        <label class="relative block max-w-xl">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-[#86868B]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
            </span>
            <input
                type="search"
                name="q"
                value="{{ $q }}"
                placeholder="Search apps or authors…"
                class="store-search w-full pl-9"
                autocomplete="off"
            >
        </label>

        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <label class="block w-full sm:min-w-[180px] sm:flex-1 sm:max-w-[220px]">
                <span class="mb-1.5 block text-[12px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">Author</span>
                <select name="author" class="filter-select" onchange="this.form.submit()">
                    <option value="">All authors</option>
                    @foreach ($authors as $authorOption)
                        <option value="{{ $authorOption }}" @selected($author === $authorOption)>{{ $authorOption }}</option>
                    @endforeach
                </select>
            </label>

            <label class="block w-full sm:min-w-[140px] sm:flex-1 sm:max-w-[180px]">
                <span class="mb-1.5 block text-[12px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">Platform</span>
                <select name="platform" class="filter-select" onchange="this.form.submit()">
                    <option value="">All platforms</option>
                    @foreach (\App\Models\AppListing::PLATFORM_LABELS as $value => $label)
                        <option value="{{ $value }}" @selected($platform === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <label class="block w-full sm:min-w-[160px] sm:flex-1 sm:max-w-[200px]">
                <span class="mb-1.5 block text-[12px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">Category</span>
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected(($category?->slug ?? '') === $cat->slug)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </label>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-get !normal-case !tracking-normal">Search</button>
                @if ($hasFilters)
                    <a href="{{ route('search') }}" class="see-all py-2">Clear</a>
                @endif
            </div>
        </div>
    </form>

    @if ($authors->isNotEmpty())
        <div class="mb-6">
            <p class="mb-2 text-[12px] font-semibold uppercase tracking-[0.06em] text-[#86868B]">Browse by author</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($authors as $authorChip)
                    <a
                        href="{{ route('search', array_filter(['author' => $authorChip, 'q' => $q ?: null, 'platform' => $platform ?: null, 'category' => $category?->slug])) }}"
                        class="filter-chip {{ $author === $authorChip ? 'filter-chip-active' : '' }}"
                    >{{ $authorChip }}</a>
                @endforeach
            </div>
        </div>
    @endif

    @if ($hasFilters)
        <p class="mb-3 text-[14px] text-[#86868B]">
            {{ $apps->count() }} {{ \Illuminate\Support\Str::plural('result', $apps->count()) }}
            @if ($q)
                for “{{ $q }}”
            @endif
            @if ($author)
                · author <span class="font-medium text-[#1D1D1F]">{{ $author }}</span>
            @endif
            @if ($platform)
                · {{ \App\Models\AppListing::PLATFORM_LABELS[$platform] ?? ucfirst($platform) }}
            @endif
            @if ($category)
                · {{ $category->name }}
            @endif
        </p>
    @endif

    @if (! $hasFilters)
        <div class="rounded-[28px] bg-[#F5F5F7] px-6 py-14 text-center text-[15px] text-[#86868B]">
            Type a search, pick an author, or use the filters above.
        </div>
    @elseif ($apps->isEmpty())
        <div class="rounded-[28px] bg-[#F5F5F7] px-6 py-14 text-center text-[15px] text-[#86868B]">
            No apps matched your search.
            <a href="{{ route('search') }}" class="see-all mt-3 inline-flex">Clear filters</a>
        </div>
    @else
        <div class="rounded-[22px] bg-[#F5F5F7] px-3 sm:px-4">
            @foreach ($apps as $app)
                @include('partials.app-row', ['app' => $app, 'showAuthor' => true])
            @endforeach
        </div>
    @endif
</section>
@endsection
