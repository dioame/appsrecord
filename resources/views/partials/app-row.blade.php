<a href="{{ route('apps.public', $app->slug) }}" class="app-row group">
    <div class="app-icon h-12 w-12 sm:h-[60px] sm:w-[60px]">
        @if ($app->logoUrl())
            <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full items-center justify-center bg-[#E8E8ED] text-[#86868B]">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.5"/></svg>
            </div>
        @endif
    </div>

    <div class="min-w-0 flex-1">
        <div class="flex min-w-0 items-center gap-2">
            <h3 class="truncate text-[14px] font-normal leading-tight text-[#1D1D1F] sm:text-[15px]">{{ $app->name }}</h3>
            <x-platform-badge :platform="$app->platform" />
        </div>
        <p class="mt-0.5 truncate text-[12px] leading-tight text-[#86868B] sm:text-[13px]">
            @if (! empty($showAuthor))
                {{ $app->authorName() }}
            @else
                {{ \Illuminate\Support\Str::limit($app->description, 48) }}
            @endif
        </p>
        <div class="mt-1">
            <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" />
        </div>
    </div>

    <span class="btn-get" tabindex="-1">View</span>
</a>
