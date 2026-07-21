@php
    $platform = $app->platform ?? 'mobile';
    $shotCardClass = match ($platform) {
        'web' => 'shot-card shot-card-wide',
        'desktop' => 'shot-card shot-card-wide',
        default => 'shot-card',
    };
@endphp

<div class="{{ $shotCardClass }}">
    <p class="px-5 pt-5 text-[15px] font-semibold leading-snug text-[#00C7BE]">
        {{ $caption }}
    </p>

    <div class="flex flex-1 items-end justify-center px-4 pb-5 pt-4">
        @if ($platform === 'web')
            <div class="browser-frame w-full">
                <div class="browser-chrome">
                    <span class="browser-dot bg-[#FF5F57]"></span>
                    <span class="browser-dot bg-[#FEBC2E]"></span>
                    <span class="browser-dot bg-[#28C840]"></span>
                    <span class="browser-url">{{ \Illuminate\Support\Str::slug($app->name) }}.app</span>
                </div>
                <img src="{{ $shot }}" alt="{{ $app->name }} screenshot" class="aspect-[16/10] w-full object-cover object-top">
            </div>
        @elseif ($platform === 'desktop')
            <div class="desktop-frame w-full">
                <div class="desktop-bezel">
                    <img src="{{ $shot }}" alt="{{ $app->name }} screenshot" class="aspect-[16/10] w-full object-cover object-top">
                </div>
                <div class="desktop-stand"></div>
                <div class="desktop-base"></div>
            </div>
        @else
            <div class="phone-frame">
                <img src="{{ $shot }}" alt="{{ $app->name }} screenshot" class="aspect-[9/19] w-full object-cover">
            </div>
        @endif
    </div>
</div>
