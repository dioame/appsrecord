<section
    x-data="{
        skills: {{ \Illuminate\Support\Js::from(old('skills', $user->skillList())) }},
        experience: {{ \Illuminate\Support\Js::from(collect(old('experience', $user->experienceEntries() ?: [['title' => '', 'company' => '', 'period' => '', 'description' => '']]))->map(fn ($row) => [
            'title' => $row['title'] ?? '',
            'company' => $row['company'] ?? '',
            'period' => $row['period'] ?? '',
            'description' => $row['description'] ?? '',
        ])->values()) }},
        education: {{ \Illuminate\Support\Js::from(collect(old('education', $user->educationEntries() ?: [['school' => '', 'degree' => '', 'period' => '', 'description' => '']]))->map(fn ($row) => [
            'school' => $row['school'] ?? '',
            'degree' => $row['degree'] ?? '',
            'period' => $row['period'] ?? '',
            'description' => $row['description'] ?? '',
        ])->values()) }},
        skillDraft: '',
        addSkill() {
            const value = (this.skillDraft || '').trim();
            if (!value || this.skills.length >= 30) return;
            const exists = this.skills.some((s) => s.toLowerCase() === value.toLowerCase());
            if (!exists) this.skills.push(value);
            this.skillDraft = '';
        }
    }"
>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('CV & skills') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add details clients see on your public portfolio link — alongside your apps.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" id="cv-form">
        @csrf
        @method('patch')
        <input type="hidden" name="_section" value="cv">

        <div>
            <x-input-label for="headline" :value="__('Headline')" />
            <x-text-input id="headline" name="headline" type="text" class="mt-1 block w-full" :value="old('headline', $user->headline)" placeholder="Full-stack developer · Product designer" autocomplete="organization-title" />
            <p class="mt-1 text-sm text-gray-600">Short professional title shown on your CV.</p>
            <x-input-error class="mt-2" :messages="$errors->get('headline')" />
        </div>

        <div>
            <x-input-label for="location" :value="__('Location (optional)')" />
            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $user->location)" placeholder="Manila, Philippines" autocomplete="address-level2" />
            <x-input-error class="mt-2" :messages="$errors->get('location')" />
        </div>

        <div>
            <x-input-label :value="__('Skills')" />
            <div class="mt-2 flex flex-wrap gap-2" x-show="skills.length" x-cloak>
                <template x-for="(skill, index) in skills" :key="skill + '-' + index">
                    <span class="inline-flex items-center gap-1 rounded-full bg-[#F5F5F7] px-3 py-1 text-[13px] font-medium text-[#1D1D1F]">
                        <span x-text="skill"></span>
                        <input type="hidden" :name="'skills[' + index + ']'" :value="skill">
                        <button type="button" class="rounded-full p-0.5 text-[#86868B] hover:bg-white hover:text-[#1D1D1F]" @click="skills.splice(index, 1)" aria-label="Remove skill">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </span>
                </template>
            </div>
            <div class="mt-2 flex gap-2">
                <x-text-input
                    id="skill_draft"
                    type="text"
                    class="block w-full"
                    x-model="skillDraft"
                    @keydown.enter.prevent="addSkill()"
                    placeholder="e.g. Laravel, React, UI design"
                />
                <button type="button" class="shrink-0 rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-700" @click="addSkill()">Add</button>
            </div>
            <p class="mt-1 text-sm text-gray-600">Press Enter or Add. Up to 30 skills.</p>
            <x-input-error class="mt-2" :messages="$errors->get('skills')" />
            <x-input-error class="mt-2" :messages="$errors->get('skills.*')" />
        </div>

        <div>
            <div class="flex items-center justify-between gap-3">
                <x-input-label :value="__('Experience')" />
                <button type="button" class="text-sm font-medium text-[#0071E3] hover:underline" @click="experience.push({ title: '', company: '', period: '', description: '' })">+ Add role</button>
            </div>
            <div class="mt-3 space-y-4">
                <template x-for="(job, index) in experience" :key="'exp-' + index">
                    <div class="space-y-3 rounded-xl border border-gray-200 p-4">
                        <div class="flex justify-between gap-2">
                            <p class="text-sm font-medium text-gray-900" x-text="'Role ' + (index + 1)"></p>
                            <button type="button" class="text-sm text-red-600 hover:underline" x-show="experience.length > 1" @click="experience.splice(index, 1)">Remove</button>
                        </div>
                        <div>
                            <x-input-label :value="__('Job title')" />
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'experience[' + index + '][title]'" x-model="job.title" placeholder="Senior developer">
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <x-input-label :value="__('Company')" />
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'experience[' + index + '][company]'" x-model="job.company" placeholder="Acme Inc.">
                            </div>
                            <div>
                                <x-input-label :value="__('Period')" />
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'experience[' + index + '][period]'" x-model="job.period" placeholder="2022 – Present">
                            </div>
                        </div>
                        <div>
                            <x-input-label :value="__('Description (optional)')" />
                            <textarea rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'experience[' + index + '][description]'" x-model="job.description" placeholder="What you shipped or owned"></textarea>
                        </div>
                    </div>
                </template>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('experience')" />
        </div>

        <div>
            <div class="flex items-center justify-between gap-3">
                <x-input-label :value="__('Education')" />
                <button type="button" class="text-sm font-medium text-[#0071E3] hover:underline" @click="education.push({ school: '', degree: '', period: '', description: '' })">+ Add school</button>
            </div>
            <div class="mt-3 space-y-4">
                <template x-for="(item, index) in education" :key="'edu-' + index">
                    <div class="space-y-3 rounded-xl border border-gray-200 p-4">
                        <div class="flex justify-between gap-2">
                            <p class="text-sm font-medium text-gray-900" x-text="'School ' + (index + 1)"></p>
                            <button type="button" class="text-sm text-red-600 hover:underline" x-show="education.length > 1" @click="education.splice(index, 1)">Remove</button>
                        </div>
                        <div>
                            <x-input-label :value="__('School')" />
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'education[' + index + '][school]'" x-model="item.school" placeholder="University name">
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <x-input-label :value="__('Degree / focus')" />
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'education[' + index + '][degree]'" x-model="item.degree" placeholder="B.S. Computer Science">
                            </div>
                            <div>
                                <x-input-label :value="__('Period')" />
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'education[' + index + '][period]'" x-model="item.period" placeholder="2018 – 2022">
                            </div>
                        </div>
                        <div>
                            <x-input-label :value="__('Notes (optional)')" />
                            <textarea rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :name="'education[' + index + '][description]'" x-model="item.description" placeholder="Honors, coursework, etc."></textarea>
                        </div>
                    </div>
                </template>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('education')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save CV') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
