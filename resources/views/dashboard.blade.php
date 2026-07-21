<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-[12px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Library</p>
                <h2 class="font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">My Apps</h2>
            </div>
            <a href="{{ route('my-apps.create') }}" class="btn-primary">+ Add</a>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="mx-auto max-w-[980px] px-4 sm:px-5">
            @if (session('status'))
                <div class="mb-4 rounded-xl bg-emerald-50 px-3 py-2 text-[13px] text-emerald-800" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($apps->isEmpty())
                <div class="rounded-2xl bg-white px-6 py-12 text-center">
                    <h3 class="font-display text-[18px] font-bold text-[#1D1D1F]">No apps yet</h3>
                    <p class="mx-auto mt-1.5 max-w-sm text-[14px] text-[#86868B]">Add a name, description, logo, and up to 3 screenshots.</p>
                    <a href="{{ route('my-apps.create') }}" class="btn-primary mt-5">Create your first app</a>
                </div>
            @else
                <div class="rounded-2xl bg-white px-3 sm:px-4">
                    @foreach ($apps as $app)
                        <div class="app-row !cursor-default hover:!opacity-100">
                            <div class="app-icon h-[52px] w-[52px]">
                                @if ($app->logoUrl())
                                    <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[15px] text-[#1D1D1F]">{{ $app->name }}</p>
                                <p class="mt-0.5 text-[12px] text-[#86868B]">
                                    {{ $app->platformLabel() }} · {{ $app->category->name }}
                                    ·
                                    @if ($app->is_published)
                                        <span class="text-[#34C759]">Published</span>
                                    @else
                                        <span class="text-[#FF9F0A]">Draft</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex shrink-0 items-center gap-1">
                                @if ($app->is_published)
                                    <a href="{{ route('apps.public', $app->slug) }}" class="btn-get !px-3" target="_blank" rel="noopener">View</a>
                                @endif
                                <a href="{{ route('my-apps.edit', $app) }}" class="btn-ghost text-[13px] text-[#0071E3]">Edit</a>
                                <form method="POST" action="{{ route('my-apps.destroy', $app) }}" onsubmit="return confirm('Delete this app?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ghost text-[13px] text-[#FF3B30]">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
