@php
    $subAuthors = $app->subAuthorEntries();
@endphp
@if (count($subAuthors) > 0)
    <section class="mt-6 rounded-2xl bg-white px-4 py-5 sm:px-5">
        <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Sub authors</p>
        <ul class="mt-3 divide-y divide-[#F0F0F2]">
            @foreach ($subAuthors as $person)
                <li class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1 py-2.5 first:pt-0 last:pb-0">
                    <span class="text-[14px] font-medium text-[#1D1D1F]">{{ $person['name'] }}</span>
                    @if (! empty($person['email']))
                        <a href="mailto:{{ $person['email'] }}" class="text-[13px] text-[#0071E3] hover:underline">{{ $person['email'] }}</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
@endif
