<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center cursor-pointer px-6 py-3 bg-[#2563EB] border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-[#1d4ed8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#2563EB] focus-visible:ring-offset-2 transition ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
