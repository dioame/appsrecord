@extends('layouts.public')

@section('title', 'Docs')
@section('meta_description', 'How to use AppsRecord — showcase your projects with screenshots, descriptions, and shareable public links.')

@section('content')
<div class="store-main-inner pb-14">
    <header class="mb-8 max-w-2xl sm:mb-10">
        <p class="text-[12px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">Docs</p>
        <h1 class="mt-2 font-display text-[28px] font-bold tracking-tight text-[#1D1D1F] sm:text-[34px]">How to use AppsRecord</h1>
        <p class="mt-3 text-[15px] leading-relaxed text-[#86868B] sm:text-[16px]">
            AppsRecord is where you compile all your project outputs into one showcase —
            plus a CV with skills, experience, and education.
            Share a public link with clients so they can see your apps and profile
            without rebuilding a portfolio every time.
        </p>
    </header>

    <div class="mb-10 grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl bg-[#F5F5F7] px-4 py-5">
            <p class="text-[13px] font-semibold text-[#1D1D1F]">Collect projects</p>
            <p class="mt-1.5 text-[13px] leading-relaxed text-[#86868B]">Add each app or deliverable once — logo, screenshots, description, and link.</p>
        </div>
        <div class="rounded-2xl bg-[#F5F5F7] px-4 py-5">
            <p class="text-[13px] font-semibold text-[#1D1D1F]">Build your CV</p>
            <p class="mt-1.5 text-[13px] leading-relaxed text-[#86868B]">Add skills, experience, and education in Profile so clients see more than apps.</p>
        </div>
        <div class="rounded-2xl bg-[#F5F5F7] px-4 py-5">
            <p class="text-[13px] font-semibold text-[#1D1D1F]">Share one link</p>
            <p class="mt-1.5 text-[13px] leading-relaxed text-[#86868B]">Send clients your public URL — portfolio apps and CV in the same place.</p>
        </div>
    </div>

    <div class="space-y-10 max-w-2xl">
        <section>
            <h2 class="section-title">1. Create your account</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                Sign up, then set your <span class="font-medium text-[#1D1D1F]">public link</span> in Profile
                (for example <code class="rounded bg-[#F5F5F7] px-1.5 py-0.5 text-[13px] text-[#1D1D1F]">/creators/your-name</code>).
                That becomes the portfolio + CV URL you share with clients.
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
            <h2 class="section-title">2. Add your CV details</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                In <span class="font-medium text-[#1D1D1F]">Profile → CV & skills</span>, add the details clients need beyond your apps:
            </p>
            <ul class="mt-3 space-y-2 text-[15px] text-[#86868B]">
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Headline & location</span> — how you introduce yourself</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Skills</span> — tools and strengths as tags</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Experience</span> — roles, companies, and what you shipped</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Education</span> — schools and degrees</span></li>
            </ul>
            @auth
                <a href="{{ route('profile.edit') }}" class="btn-secondary mt-4">Edit CV</a>
            @endauth
        </section>

        <section>
            <h2 class="section-title">3. Submit a project</h2>
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
            <h2 class="section-title">4. Share with clients</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                Instead of assembling a new portfolio for every pitch, send one lasting link:
            </p>
            <ul class="mt-3 space-y-2 text-[15px] text-[#86868B]">
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Creator portfolio</span> — published apps plus your CV (skills, experience, education)</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">CV PDF</span> — download includes your photo, CV details, app logos/screenshots, and deployed apps list</span></li>
                <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0071E3]"></span><span><span class="font-medium text-[#1D1D1F]">Single app page</span> — deep-link one project with screenshots, description, and access link</span></li>
            </ul>
            <p class="mt-3 text-[15px] leading-relaxed text-[#86868B]">
                Clients browse apps or switch to your CV tab at their own pace — then jump into a live project when they want more.
            </p>
        </section>

        <section>
            <h2 class="section-title">5. Keep adding — stay current</h2>
            <p class="mt-2 text-[15px] leading-relaxed text-[#86868B]">
                Every new submission updates your showcase automatically. Edit CV or unpublish anytime from Profile and Library.
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
            apps for proof of work, CV for who you are, and links so they can experience the real product.
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
