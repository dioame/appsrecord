<x-guest-layout>
    <h1 class="mb-1 font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">Sign In</h1>
    <p class="mb-5 text-[13px] text-[#86868B]">Welcome back to AppsRecord</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-wamisso-login-button />
    <p class="mt-5 text-center text-[13px] text-[#86868B]">
        New here?
        <a href="{{ route('register') }}" class="font-semibold text-[#0071E3] hover:opacity-70 cursor-pointer">Create an account</a>
    </p>
</x-guest-layout>
