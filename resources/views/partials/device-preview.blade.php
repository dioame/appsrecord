@php
    $platform = $app->platform ?? 'mobile';
    $shotCardClass = match ($platform) {
        'web', 'desktop', 'others' => 'shot-card shot-card-wide',
        default => 'shot-card',
    };
    $index = $index ?? 0;
@endphp

<button
    type="button"
    class="{{ $shotCardClass }} group cursor-zoom-in text-left transition hover:opacity-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0071E3]"
    @click="openAt({{ $index }})"
    aria-label="Zoom screenshot {{ $index + 1 }}"
>
    <p class="px-5 pt-5 text-[15px] font-semibold leading-snug text-[#00C7BE]">
        {{ $caption }}
    </p>

    <div class="relative flex flex-1 items-end justify-center px-4 pb-5 pt-4">
        @if ($platform === 'web')
            <div class="browser-frame w-full">
                <div class="browser-chrome">
                    <span class="browser-dot bg-[#FF5F57]"></span>
                    <span class="browser-dot bg-[#FEBC2E]"></span>
                    <span class="browser-dot bg-[#28C840]"></span>
                    <span class="browser-url">{{ \Illuminate\Support\Str::slug($app->name) }}.app</span>
                </div>
                <img src="{{ $shot }}" alt="{{ $app->name }} screenshot {{ $index + 1 }}" class="aspect-[16/10] w-full object-cover object-top transition duration-200 group-hover:brightness-95">
            </div>
        @elseif ($platform === 'desktop')
            <div class="desktop-frame w-full">
                <div class="desktop-bezel">
                    <img src="{{ $shot }}" alt="{{ $app->name }} screenshot {{ $index + 1 }}" class="aspect-[16/10] w-full object-cover object-top transition duration-200 group-hover:brightness-95">
                </div>
                <div class="desktop-stand"></div>
                <div class="desktop-base"></div>
            </div>
        @elseif ($platform === 'others')
            <div class="w-full overflow-hidden rounded-2xl bg-white shadow-[0_8px_24px_rgba(0,0,0,0.12)] ring-1 ring-black/5">
                <img src="{{ $shot }}" alt="{{ $app->name }} screenshot {{ $index + 1 }}" class="aspect-[16/10] w-full object-cover object-top transition duration-200 group-hover:brightness-95">
            </div>
        @else
            <div class="phone-frame">
                <img src="{{ $shot }}" alt="{{ $app->name }} screenshot {{ $index + 1 }}" class="aspect-[9/19] w-full object-cover transition duration-200 group-hover:brightness-95">
            </div>
        @endif

        <span class="pointer-events-none absolute bottom-7 right-6 inline-flex items-center gap-1 rounded-full bg-black/55 px-2.5 py-1 text-[11px] font-medium text-white opacity-0 transition group-hover:opacity-100">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15zM10.5 8v5M8 10.5h5"/></svg>
            Zoom
        </span>
    </div>
</button>
