<a href="{{ route('apps.public', $app->slug) }}" class="editorial-card overflow-hidden" style="background: linear-gradient(165deg, #1d1d1f 0%, #2c2c2e 50%, #0071e3 140%);">
    <div class="relative flex min-h-[220px] flex-col justify-between p-5 sm:min-h-[250px]">
        @if (($app->imageUrls()[0] ?? null))
            <img src="{{ $app->imageUrls()[0] }}" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-30">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
        @endif
        <div class="relative">
            <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-white/70">{{ $eyebrow ?? 'FEATURED APP' }}</p>
            <h3 class="mt-2 font-display text-[24px] font-bold leading-tight tracking-tight sm:text-[26px]">{{ $app->name }}</h3>
            <p class="mt-1.5 line-clamp-2 text-[13px] text-white/80">{{ $app->description }}</p>
        </div>
        <div class="relative mt-5 flex items-center gap-3">
            <div class="app-icon h-11 w-11 ring-1 ring-white/25">
                @if ($app->logoUrl())
                    <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-[13px] font-medium text-white">{{ $app->category->name ?? 'Apps' }}</p>
                <p class="truncate text-[12px] text-white/60">{{ $app->user->name ?? 'Publisher' }}</p>
            </div>
            <span class="inline-flex rounded-full bg-white px-3.5 py-1.5 text-[12px] font-bold uppercase tracking-wide text-[#0071E3]" tabindex="-1">View</span>
        </div>
    </div>
</a>
