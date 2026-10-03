<x-guest-layout>
    <h1 class="mb-1 font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">Create Account</h1>
    <p class="mb-5 text-[13px] text-[#86868B]">Publish apps to the AppsRecord store</p>

    <x-wamisso-login-button />
    <p class="mt-5 text-center text-[13px] text-[#86868B]">
        Already registered?
        <a href="{{ route('login') }}" class="font-semibold text-[#0071E3] hover:opacity-70 cursor-pointer">Sign in</a>
    </p>
</x-guest-layout>
