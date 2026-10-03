@if ($errors->has('wamisso'))
    <div class="mb-3 rounded-xl bg-red-50 px-3 py-2 text-[13px] text-red-700" role="alert">
        {{ $errors->first('wamisso') }}
    </div>
@endif

@if (\App\Http\Controllers\Auth\WamissoAuthController::configured())
    <a href="{{ route('auth.wamisso') }}"
       class="mb-3 flex w-full cursor-pointer items-center justify-center gap-2.5 rounded-full bg-[#0071E3] px-4 py-2.5 text-[14px] font-semibold text-white transition-colors duration-150 hover:bg-[#0077ED] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0071E3]">
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 2l8 3v6c0 5-3.4 9.3-8 11-4.6-1.7-8-6-8-11V5l8-3z"/>
            <path d="M9 12l2 2 4-4"/>
        </svg>
        Sign in with WamISSO
    </a>
@endif
