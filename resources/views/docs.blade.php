@extends('layouts.public')

@section('title', 'Docs')
@section('meta_description', 'How to use AppsRecord — showcase your projects with screenshots, descriptions, and shareable public links.')

@section('content')
<div class="store-main-inner pb-14">
    <header class="mb-8 max-w-2xl sm:mb-10">
        <p class="text-[12px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Docs</p>
        <h1 class="mt-2 font-display text-[28px] font-bold tracking-tight text-[#1D1D1F] sm:text-[34px]">How to use AppsRecord</h1>
        <p class="mt-3 text-[15px] leading-relaxed text-[#86868B] sm:text-[16px]">
            AppsRecord is where you compile all your project outputs into one showcase.
            Share a public link with clients so they can see screenshots, read descriptions,
            and open your live projects — without rebuilding your portfolio every time.
        </p>
    </header>

    <div class="mb-10 grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl bg-[#F5F5F7] px-4 py-5">
            <p class="text-[13px] font-semibold text-[#1D1D1F]">Collect projects</p>
            <p class="mt-1.5 text-[13px] leading-relaxed text-[#86868B]">Add each app or deliverable once — logo, screenshots, description, and link.</p>
        </div>
        <div class="rounded-2xl bg-[#F5F5F7] px-4 py-5">
            <p class="text-[13px] font-semibold text-[#1D1D1F]">Share one link</p>
            <p class="mt-1.5 text-[13px] leading-relaxed text-[#86868B]">Send clients your public portfolio URL. No more recompiling decks for every submission.</p>
        </div>
        <div class="rounded-2xl bg-[#F5F5F7] px-4 py-5">
            <p class="text-[13px] font-semibold text-[#1D1D1F]">Let them explore</p>
            <p class="mt-1.5 text-[13px] leading-relaxed text-[#86868B]">Clients glimpse screenshots and details, then open the project link to try it live.</p>
        </div>
    </div>

    <div class="space-y-10 max-w-2xl">
        <section>
            <h2 class="section-title">1. Create your account</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                Sign up, then set your <span class="font-medium text-[#1D1D1F]">public link</span> in Profile
                (for example <code class="rounded bg-[#F5F5F7] px-1.5 py-0.5 text-[13px] text-[#1D1D1F]">/creators/your-name</code>).
                That becomes the portfolio URL you share with clients.
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                @guest
                    <a href="{{ route('register') }}" class="btn-primary">Get started</a>
                    <a href="{{ route('login') }}" class="btn-secondary">Sign in</a>
                @else
                    <a href="{{ route('profile.edit') }}" class="btn-primary">Edit profile</a>
                    @if (auth()->user()->slug)
                        <a href="{{ route('creators.show', auth()->user()->slug) }}" class="btn-secondary" target="_blank" rel="noopener">Open my portfolio</a>
                    @endif
                @endguest
            </div>
        </section>

        <section>
            <h2 class="section-title">2. Submit a project</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                From <span class="font-medium text-[#1D1D1F]">Submit</span> or <span class="font-medium text-[#1D1D1F]">Library → + Add</span>, publish each project with:
            </p>
            <ul class="mt-3 space-y-2 text-[15px] text-[#86868B]">
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Name & author</span> — how it appears in the store</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Category & platform</span> — Mobile, Web, Desktop, or Others</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Description</span> — what it does and who it’s for</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Logo & screenshots</span> — up to 3 shots so clients get a quick glimpse</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">App link</span> — store URL, demo, or live site they can open</span></li>
            </ul>
            <p class="mt-3 text-[15px] leading-relaxed text-[#86868B]">
                Mark it <span class="font-medium text-[#1D1D1F]">Published</span> when you’re ready for it to appear on the store and your public portfolio.
            </p>
            @auth
                <a href="{{ route('my-apps.create') }}" class="btn-primary mt-4">Submit a project</a>
            @endauth
        </section>

        <section>
            <h2 class="section-title">3. Share with clients</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                Instead of assembling a new portfolio for every pitch, send one lasting link:
            </p>
            <ul class="mt-3 space-y-2 text-[15px] text-[#86868B]">
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Creator portfolio</span> — all your published projects in one place</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Single app page</span> — deep-link one project with screenshots, description, and access link</span></li>
            </ul>
            <p class="mt-3 text-[15px] leading-relaxed text-[#86868B]">
                Clients browse at their own pace, see what you built, and jump into the live project when they want more.
            </p>
        </section>

        <section>
            <h2 class="section-title">4. Keep adding — stay current</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                Every new submission updates your showcase automatically. Edit or unpublish anytime from Library.
                Your public link stays the same, so clients always see your latest work without another rebuild.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-secondary mt-4">Go to Library</a>
            @endauth
        </section>
    </div>

    <aside class="mt-12 max-w-2xl rounded-2xl border border-[#D2D2D7] bg-white px-5 py-6 sm:px-6">
        <h2 class="font-display text-[18px] font-bold text-[#1D1D1F]">Why AppsRecord?</h2>
        <p class="mt-2 text-[14px] leading-relaxed text-[#86868B] sm:text-[15px]">
            Stop compiling the same portfolio for every client. Publish once, share forever —
            screenshots for a quick look, descriptions for context, and links so they can experience the real product.
        </p>
        <div class="mt-4 flex flex-wrap gap-2">
            <a href="{{ route('home') }}" class="btn-secondary">Browse apps</a>
            @guest
                <a href="{{ route('register') }}" class="btn-primary">Create account</a>
            @else
                <a href="{{ route('my-apps.create') }}" class="btn-primary">Add your first project</a>
            @endguest
        </div>
    </aside>
</div>
@endsection
