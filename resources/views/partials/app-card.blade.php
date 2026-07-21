<a href="{{ route('apps.public', $app->slug) }}" class="featured-card">
    <div class="aspect-[16/10] overflow-hidden bg-[#E8E8ED]">
        @php $preview = $app->imageUrls()[0] ?? $app->logoUrl(); @endphp
        @if ($preview)
            <img src="{{ $preview }}" alt="{{ $app->name }}" class="h-full w-full object-cover">
        @else
            <div class="flex h-full items-center justify-center bg-gradient-to-br from-[#0071E3] to-[#0040DD]">
                @if ($app->logoUrl())
                    <img src="{{ $app->logoUrl() }}" alt="" class="h-14 w-14 rounded-[22%] object-cover">
                @endif
            </div>
        @endif
    </div>
    <div class="flex items-center gap-2.5 p-3">
        <div class="app-icon h-10 w-10">
            @if ($app->logoUrl())
                <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <p class="truncate text-[13px] font-semibold text-[#1D1D1F]">{{ $app->name }}</p>
            <p class="truncate text-[11px] text-[#86868B] sm:text-[12px]">{{ $app->platformLabel() }} · {{ $app->category->name ?? 'App' }}</p>
            <div class="mt-0.5">
                <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" />
            </div>
        </div>
        <span class="btn-get !px-3 !py-1 text-[11px]" tabindex="-1">View</span>
    </div>
</a>
