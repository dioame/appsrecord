<x-guest-layout>
    <h1 class="mb-1 font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">Create Account</h1>
    <p class="mb-5 text-[13px] text-[#86868B]">Publish apps to the AppsRecord store</p>

    <x-google-login-button />

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="form-input !mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-input !mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-input !mt-1"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="form-input !mt-1"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
            {{ __('Register') }}
        </x-primary-button>
    </form>

    <p class="mt-5 text-center text-[13px] text-[#86868B]">
        Already registered?
        <a href="{{ route('login') }}" class="font-semibold text-[#0071E3] hover:opacity-70 cursor-pointer">Sign in</a>
    </p>
</x-guest-layout>
