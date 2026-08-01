@props([
    'user' => null,
])

@if ($user?->isTrusted())
    <span {{ $attributes->class(['inline-flex shrink-0 items-center gap-0.5 rounded-full bg-[#E8F1FF] px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-[0.04em] text-[#0071E3]']) }} title="Trusted developer — apps publish without approval">
        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        Trusted
    </span>
@endif
