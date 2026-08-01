<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Admin</p>
                <h2 class="font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">Pending apps</h2>
            </div>
            <a href="{{ route('admin.users.index') }}" class="rounded-full px-3.5 py-1.5 text-[13px] font-semibold text-[#1D1D1F] hover:bg-black/5">Users</a>
        </div>
    </x-slot>

    <div class="bg-[#F5F5F7] py-6">
        <div class="mx-auto max-w-[980px] space-y-4 px-4 sm:px-5">
            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 px-3 py-2 text-[13px] text-emerald-800" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-2xl bg-white">
                <div class="border-b border-[#F0F0F2] px-4 py-3 sm:px-5">
                    <h3 class="text-[14px] font-semibold text-[#1D1D1F]">
                        {{ $apps->total() }} awaiting approval
                    </h3>
                    <p class="mt-0.5 text-[12px] text-[#86868B]">Non-trusted developers need approval before their apps appear publicly.</p>
                </div>

                <ul class="divide-y divide-[#F0F0F2]">
                    @forelse ($apps as $app)
                        <li class="px-4 py-3.5 sm:px-5">
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="app-icon h-12 w-12 sm:h-14 sm:w-14">
                                    @if ($app->logoUrl())
                                        <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="truncate text-[15px] font-medium text-[#1D1D1F]">{{ $app->name }}</p>
                                        <x-platform-badge :platform="$app->platform" />
                                        <span class="inline-flex items-center rounded-full bg-[#FFF4E5] px-2 py-0.5 text-[11px] font-semibold text-[#C93400]">Pending</span>
                                    </div>
                                    <p class="mt-0.5 truncate text-[12px] text-[#86868B]">
                                        {{ $app->category?->name }}
                                        · {{ $app->user?->name }}
                                        · {{ $app->user?->email }}
                                    </p>
                                </div>

                                <div class="flex shrink-0 items-center gap-2">
                                    <form method="POST" action="{{ route('admin.apps.approve', $app) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-full bg-[#E8F8EE] px-3.5 py-1.5 text-[13px] font-semibold text-[#248A3D] hover:bg-[#D8F3E2]">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.apps.reject', $app) }}" onsubmit="return confirm('Reject this app?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-full bg-[#FFF2F1] px-3.5 py-1.5 text-[13px] font-semibold text-[#FF3B30] hover:bg-[#FFE5E3]">Reject</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-10 text-center text-[14px] text-[#86868B] sm:px-5">No apps waiting for approval.</li>
                    @endforelse
                </ul>

                @if ($apps->hasPages())
                    <div class="border-t border-[#F0F0F2] px-4 py-3 sm:px-5">
                        {{ $apps->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
