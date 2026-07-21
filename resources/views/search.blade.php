@extends('layouts.public')

@section('title', $q ? 'Search: '.$q : 'Search')

@section('content')
<section class="store-main-inner pb-12">
    <header class="mb-6">
        <h1 class="font-display text-[34px] font-bold tracking-tight text-[#1D1D1F]">Search</h1>
        @if ($q)
            <p class="mt-2 text-[15px] text-[#86868B]">Results for “{{ $q }}”</p>
        @else
            <p class="mt-2 text-[15px] text-[#86868B]">Find apps by name or description.</p>
        @endif
    </header>

    @if (! $q)
        <div class="rounded-[28px] bg-[#F5F5F7] px-6 py-14 text-center text-[15px] text-[#86868B]">
            Type in the sidebar search to find apps.
        </div>
    @elseif ($apps->isEmpty())
        <div class="rounded-[28px] bg-[#F5F5F7] px-6 py-14 text-center text-[15px] text-[#86868B]">
            No apps matched “{{ $q }}”.
        </div>
    @else
        <div class="rounded-[22px] bg-[#F5F5F7] px-3 sm:px-4">
            @foreach ($apps as $app)
                @include('partials.app-row', ['app' => $app])
            @endforeach
        </div>
    @endif
</section>
@endsection
