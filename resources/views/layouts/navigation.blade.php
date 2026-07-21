<nav x-data="{ open: false }" class="border-b border-[#D2D2D7]/80 bg-[rgba(245,245,247,0.82)] backdrop-blur-xl">
    <div class="mx-auto flex h-12 max-w-[980px] items-center justify-between px-4 sm:px-5">
        <div class="flex items-center gap-5">
            <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer">
                <span class="flex h-7 w-7 items-center justify-center rounded-[8px] bg-gradient-to-br from-[#0071E3] to-[#5856D6] text-white">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                </span>
                <span class="font-display text-[17px] font-semibold tracking-tight">AppsRecord</span>
            </a>

            <div class="hidden items-center gap-0.5 sm:flex">
                <a href="{{ route('home') }}" class="btn-ghost">Apps</a>
                <a href="{{ route('dashboard') }}" class="btn-ghost {{ request()->routeIs('dashboard') ? 'bg-black/5' : '' }}">Library</a>
                <a href="{{ route('my-apps.create') }}" class="btn-ghost {{ request()->routeIs('my-apps.create') ? 'bg-black/5' : '' }}">Submit</a>
            </div>
        </div>

        <div class="hidden sm:block">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="btn-ghost cursor-pointer">
                        {{ Auth::user()->name }}
                        <svg class="ms-1 h-3.5 w-3.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <button @click="open = ! open" class="btn-ghost !px-2 sm:hidden cursor-pointer">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-[#D2D2D7] sm:hidden">
        <div class="space-y-0.5 px-3 py-2">
            <a href="{{ route('home') }}" class="btn-ghost justify-start w-full">Apps</a>
            <a href="{{ route('dashboard') }}" class="btn-ghost justify-start w-full">Library</a>
            <a href="{{ route('my-apps.create') }}" class="btn-ghost justify-start w-full">Submit</a>
            <a href="{{ route('profile.edit') }}" class="btn-ghost justify-start w-full">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-ghost justify-start w-full text-[#FF3B30]">Log Out</button>
            </form>
        </div>
    </div>
</nav>
