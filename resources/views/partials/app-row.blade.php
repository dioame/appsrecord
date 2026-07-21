<a href="{{ route('apps.public', $app->slug) }}" class="app-row group">
    <div class="app-icon h-[60px] w-[60px]">
        @if ($app->logoUrl())
            <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full items-center justify-center bg-[#E8E8ED] text-[#86868B]">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.5"/></svg>
            </div>
        @endif
    </div>

    <div class="min-w-0 flex-1">
        <h3 class="truncate text-[15px] font-normal leading-tight text-[#1D1D1F]">{{ $app->name }}</h3>
        <p class="mt-0.5 truncate text-[13px] leading-tight text-[#86868B]">
            @if (! empty($showAuthor))
                {{ $app->authorName() }} · {{ $app->platformLabel() }}
            @else
                {{ $app->platformLabel() }} · {{ \Illuminate\Support\Str::limit($app->description, 40) }}
            @endif
        </p>
    </div>

    <span class="btn-get" tabindex="-1">View</span>
</a>
