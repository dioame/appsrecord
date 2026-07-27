@props([
    'gapClass' => '',
    'scrollStep' => 360,
])
<div
    class="relative"
    x-data="{
        canPrev: false,
        canNext: false,
        update() {
            const el = this.$refs.shelf;
            if (!el) return;
            this.canPrev = el.scrollLeft > 4;
            this.canNext = el.scrollLeft + el.clientWidth < el.scrollWidth - 4;
        },
        scroll(dir) {
            const el = this.$refs.shelf;
            if (!el) return;
            el.scrollBy({ left: dir * Math.min({{ (int) $scrollStep }}, el.clientWidth * 0.85), behavior: 'smooth' });
            this.$nextTick(() => this.update());
        },
    }"
    x-init="
        update();
        $refs.shelf.addEventListener('scroll', () => update(), { passive: true });
        new ResizeObserver(() => update()).observe($refs.shelf);
        requestAnimationFrame(() => update());
    "
>
    <button
        type="button"
        class="shelf-arrow shelf-arrow-left"
        x-show="canPrev"
        x-cloak
        x-transition.opacity
        @click.stop="scroll(-1)"
        aria-label="Scroll left"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>

    <div class="store-shelf {{ $gapClass }}" x-ref="shelf">
        {{ $slot }}
    </div>

    <button
        type="button"
        class="shelf-arrow shelf-arrow-right"
        x-show="canNext"
        x-cloak
        x-transition.opacity
        @click.stop="scroll(1)"
        aria-label="Scroll right"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>
</div>
