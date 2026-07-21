@extends('layouts.public')

@section('title', $category->name)

@section('content')
<section class="store-main-inner pb-12">
    <header class="mb-6">
        <p class="text-[12px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Category</p>
        <h1 class="mt-1 font-display text-[34px] font-bold tracking-tight text-[#1D1D1F]">{{ $category->name }}</h1>
        <p class="mt-2 max-w-2xl text-[15px] text-[#86868B]">{{ $category->description }}</p>
    </header>

    @if ($apps->isEmpty())
        <div class="rounded-[28px] bg-[#F5F5F7] px-6 py-14 text-center">
            <p class="text-[15px] text-[#86868B]">No apps in this category yet.</p>
            @auth
                <a href="{{ route('my-apps.create') }}" class="btn-primary mt-4">Add the first app</a>
            @endauth
        </div>
    @else
        <div class="rounded-[22px] bg-[#F5F5F7] px-3 sm:px-4">
            @php $columns = $apps->chunk(ceil(max($apps->count(), 1) / 2)); @endphp
            <div class="grid sm:grid-cols-2 sm:gap-x-8">
                @foreach ($columns as $columnApps)
                    <div>
                        @foreach ($columnApps as $app)
                            @include('partials.app-row', ['app' => $app])
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-6">
            {{ $apps->links() }}
        </div>
    @endif
</section>
@endsection
