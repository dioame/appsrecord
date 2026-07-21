@props([
    'app',
    'userRating' => null,
])

@php
    $avg = $app->averageRating();
    $count = $app->ratingsCount();
    $current = (int) ($userRating ?? 0);
@endphp

<section class="mt-6 rounded-[18px] bg-[#F5F5F7] px-4 py-4 sm:rounded-[22px] sm:px-5 sm:py-5">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
        <div>
            <h2 class="text-[17px] font-semibold text-[#1D1D1F]">Ratings</h2>
            <div class="mt-2 flex items-center gap-3">
                <p class="font-display text-[32px] font-bold leading-none tabular-nums text-[#1D1D1F] sm:text-[36px]">
                    {{ $avg > 0 ? number_format($avg, 1) : '—' }}
                </p>
                <div>
                    <x-star-rating :rating="$avg" :count="$count" size="md" />
                    <p class="mt-1 text-[12px] text-[#86868B]">
                        {{ $count }} {{ \Illuminate\Support\Str::plural('rating', $count) }} · 5 is highest
                    </p>
                </div>
            </div>
        </div>

        <div class="sm:text-right">
            @auth
                <p class="mb-2 text-[13px] text-[#86868B]">
                    {{ $current ? 'Your rating — tap a star to update' : 'Rate this app' }}
                </p>
                <form
                    method="POST"
                    action="{{ route('apps.rate', $app->slug) }}"
                    x-data="{ hover: 0, current: {{ $current }} }"
                    class="inline-flex items-center gap-0.5"
                    @mouseleave="hover = 0"
                >
                    @csrf
                    @for ($i = 1; $i <= 5; $i++)
                        <button
                            type="submit"
                            name="rating"
                            value="{{ $i }}"
                            class="rating-star"
                            :class="(hover || current) >= {{ $i }} ? 'text-amber-400' : 'text-[#D2D2D7]'"
                            @mouseenter="hover = {{ $i }}"
                            aria-label="Rate {{ $i }} out of 5"
                        >
                            <svg class="h-9 w-9 sm:h-8 sm:w-8" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                    @endfor
                </form>
            @else
                <p class="text-[13px] text-[#86868B]">Sign in to leave a rating</p>
                <a href="{{ route('login') }}" class="btn-primary mt-2 !px-4">Sign In to Rate</a>
            @endauth
        </div>
    </div>
</section>
