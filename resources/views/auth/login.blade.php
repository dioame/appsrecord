<x-guest-layout>
    <h1 class="mb-1 font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">Sign In</h1>
    <p class="mb-5 text-[13px] text-[#86868B]">Welcome back to AppsRecord</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-google-login-button />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-input !mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-input !mt-1"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-3">
            <label for="remember_me" class="inline-flex cursor-pointer items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[#D2D2D7] text-[#0071E3] shadow-sm focus:ring-[#0071E3]" name="remember">
                <span class="ms-2 text-[13px] text-[#86868B]">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[13px] text-[#0071E3] hover:opacity-70 cursor-pointer" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center">
            {{ __('Log in') }}
        </x-primary-button>
    </form>

    <p class="mt-5 text-center text-[13px] text-[#86868B]">
        New here?
        <a href="{{ route('register') }}" class="font-semibold text-[#0071E3] hover:opacity-70 cursor-pointer">Create an account</a>
    </p>
</x-guest-layout>
