<nav x-data="{ navOpen: false }" class="relative z-50 border-b border-[#D2D2D7]/80 bg-[rgba(245,245,247,0.92)] backdrop-blur-xl">
    <div class="mx-auto flex h-12 max-w-[980px] items-center justify-between px-4 sm:px-5">
        <div class="flex items-center gap-5">
            <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer">
                <x-application-logo class="h-7 w-7 rounded-[8px]" />
                <span class="font-display text-[17px] font-semibold tracking-tight">AppsRecord</span>
            </a>

            <div class="hidden items-center gap-0.5 sm:flex">
                <a href="{{ route('home') }}" class="btn-ghost">Apps</a>
                <a href="{{ route('dashboard') }}" class="btn-ghost {{ request()->routeIs('dashboard') ? 'bg-black/5' : '' }}">Library</a>
                <a href="{{ route('my-apps.create') }}" class="btn-ghost {{ request()->routeIs('my-apps.*') ? 'bg-black/5' : '' }}">Submit</a>
            </div>
        </div>

        <div class="hidden sm:block">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button" class="btn-ghost cursor-pointer">
                        <span class="max-w-[160px] truncate">{{ Auth::user()->name }}</span>
                        <svg class="ms-1 h-3.5 w-3.5 shrink-0 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="border-b border-[#F0F0F2] px-3.5 py-2.5">
                        <p class="truncate text-[13px] font-semibold text-[#1D1D1F]">{{ Auth::user()->name }}</p>
                        <p class="truncate text-[11px] text-[#86868B]">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <x-dropdown-link :href="route('dashboard')">Library</x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        @if (Auth::user()->slug)
                            <x-dropdown-link :href="route('creators.show', Auth::user()->slug)" target="_blank">Public portfolio & CV</x-dropdown-link>
                        @endif
                    </div>
                    <div class="border-t border-[#F0F0F2] py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="!text-[#FF3B30]" onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </div>
                </x-slot>
            </x-dropdown>
        </div>

        <button type="button" @click="navOpen = ! navOpen" class="btn-ghost !px-2 sm:hidden cursor-pointer" aria-label="Menu">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': navOpen, 'inline-flex': ! navOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! navOpen, 'inline-flex': navOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div x-show="navOpen" x-cloak class="border-t border-[#D2D2D7] sm:hidden">
        <div class="space-y-0.5 px-3 py-2">
            <a href="{{ route('home') }}" class="btn-ghost justify-start w-full">Apps</a>
            <a href="{{ route('dashboard') }}" class="btn-ghost justify-start w-full">Library</a>
            <a href="{{ route('my-apps.create') }}" class="btn-ghost justify-start w-full">Submit</a>
            <a href="{{ route('profile.edit') }}" class="btn-ghost justify-start w-full">Profile</a>
            @if (Auth::user()->slug)
                <a href="{{ route('creators.show', Auth::user()->slug) }}" target="_blank" class="btn-ghost justify-start w-full">Public portfolio & CV</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-ghost justify-start w-full text-[#FF3B30]">Log Out</button>
            </form>
        </div>
    </div>
</nav>
