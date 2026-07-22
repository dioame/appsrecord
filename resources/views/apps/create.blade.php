<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-[#86868B] transition hover:bg-black/5 hover:text-[#1D1D1F]" aria-label="Back to library">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-display text-[22px] font-bold tracking-tight text-[#1D1D1F]">Add App</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('my-apps.store') }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-[#E4E4E7] bg-white p-6 sm:p-8">
                @csrf

                <div>
                    <label for="name" class="form-label">App name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="120" class="form-input" placeholder="e.g. FocusFlow">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label for="author" class="form-label">Author</label>
                    <input id="author" name="author" type="text" value="{{ old('author', auth()->user()->name) }}" required maxlength="120" class="form-input" placeholder="Developer or studio name">
                    <x-input-error :messages="$errors->get('author')" class="mt-2" />
                </div>

                @include('apps.partials.sub-authors-fields')

                <div>
                    <label for="category_id" class="form-label">Category</label>
                    <select id="category_id" name="category_id" required class="form-input">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <div>
                    <p class="form-label mb-2">Platform</p>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach (\App\Models\AppListing::PLATFORM_LABELS as $value => $label)
                            <label class="flex cursor-pointer flex-col items-center gap-1.5 rounded-xl border border-[#D2D2D7] bg-white px-3 py-3 text-center transition has-[:checked]:border-[#0071E3] has-[:checked]:bg-[#0071E3]/5 has-[:checked]:ring-2 has-[:checked]:ring-[#0071E3]/20">
                                <input type="radio" name="platform" value="{{ $value }}" class="sr-only" @checked(old('platform', 'mobile') === $value) required>
                                <span class="text-[13px] font-semibold text-[#1D1D1F]">{{ $label }}</span>
                                <span class="text-[11px] text-[#86868B]">
                                    @if ($value === 'mobile') Phone frame
                                    @elseif ($value === 'web') Browser frame
                                    @elseif ($value === 'desktop') Desktop frame
                                    @else Generic preview
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('platform')" class="mt-2" />
                </div>

                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="6" required maxlength="5000" class="form-input" placeholder="What does this app do? Who is it for?">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <label for="link" class="form-label">App link <span class="font-normal text-[#86868B]">(optional)</span></label>
                    <input id="link" name="link" type="url" value="{{ old('link') }}" maxlength="500" class="form-input" placeholder="https://example.com or store URL">
                    <p class="mt-2 text-xs text-[#71717A]">Website, App Store, Play Store, or download URL.</p>
                    <x-input-error :messages="$errors->get('link')" class="mt-2" />
                </div>

                <div>
                    <label for="logo" class="form-label">Logo</label>
                    <input id="logo" name="logo" type="file" accept="image/*" required class="form-input file:mr-4 file:rounded-md file:border-0 file:bg-[#18181B] file:px-3 file:py-2 file:text-sm file:font-medium file:text-white file:cursor-pointer">
                    <p class="mt-2 text-xs text-[#71717A]">PNG, JPG, WEBP or SVG. Max 2MB.</p>
                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                </div>

                <div>
                    <label for="images" class="form-label">Screenshots <span class="font-normal text-[#71717A]">(max 3)</span></label>
                    <input id="images" name="images[]" type="file" accept="image/*" multiple class="form-input file:mr-4 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-[#18181B] file:cursor-pointer">
                    <p class="mt-2 text-xs text-[#71717A]">Optional. Select up to 3 images (max 4MB each).</p>
                    <x-input-error :messages="$errors->get('images')" class="mt-2" />
                    <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_published" value="0">
                    <input id="is_published" name="is_published" type="checkbox" value="1" class="h-4 w-4 rounded border-[#E4E4E7] text-[#2563EB] focus:ring-[#2563EB]" @checked(old('is_published', true))>
                    <label for="is_published" class="text-sm text-[#18181B] cursor-pointer">Publish on landing page</label>
                </div>

                <div class="flex flex-wrap gap-3 border-t border-[#E4E4E7] pt-6">
                    <button type="submit" class="btn-primary">Publish app</button>
                    <a href="{{ route('dashboard') }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
