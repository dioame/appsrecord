@php
    $listing = $appListing ?? null;
    $initialSubAuthors = old(
        'sub_authors',
        $listing instanceof \App\Models\AppListing ? $listing->subAuthorEntries() : []
    );
    $initialSubAuthors = collect($initialSubAuthors)
        ->map(fn ($row) => [
            'name' => is_array($row) ? (string) ($row['name'] ?? '') : '',
            'email' => is_array($row) ? (string) ($row['email'] ?? '') : '',
        ])
        ->values()
        ->all();
@endphp

<div
    x-data="{
        subAuthors: {{ \Illuminate\Support\Js::from($initialSubAuthors) }},
        add() {
            if (this.subAuthors.length >= 20) return;
            this.subAuthors.push({ name: '', email: '' });
        },
        remove(index) {
            this.subAuthors.splice(index, 1);
        }
    }"
>
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="form-label mb-0">Sub authors <span class="font-normal text-[#86868B]">(optional)</span></p>
            <p class="mt-1 text-xs text-[#71717A]">Collaborators credited on this app. Email is optional. Add as many as you need.</p>
        </div>
        <button type="button" class="shrink-0 text-[13px] font-semibold text-[#0071E3] hover:underline" @click="add()">+ Add</button>
    </div>

    <div class="mt-3 space-y-3" x-show="subAuthors.length > 0" x-cloak>
        <template x-for="(person, index) in subAuthors" :key="'sub-' + index">
            <div class="rounded-xl border border-[#E4E4E7] p-3 sm:p-4">
                <div class="mb-2 flex items-center justify-between gap-2">
                    <p class="text-[13px] font-medium text-[#1D1D1F]" x-text="'Sub author ' + (index + 1)"></p>
                    <button type="button" class="text-[12px] font-medium text-red-600 hover:underline" @click="remove(index)">Remove</button>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="form-label" :for="'sub_author_name_' + index">Name</label>
                        <input
                            type="text"
                            class="form-input"
                            maxlength="120"
                            placeholder="Collaborator name"
                            :id="'sub_author_name_' + index"
                            :name="'sub_authors[' + index + '][name]'"
                            x-model="person.name"
                        >
                    </div>
                    <div>
                        <label class="form-label" :for="'sub_author_email_' + index">Email <span class="font-normal text-[#86868B]">(optional)</span></label>
                        <input
                            type="email"
                            class="form-input"
                            maxlength="255"
                            placeholder="name@example.com"
                            :id="'sub_author_email_' + index"
                            :name="'sub_authors[' + index + '][email]'"
                            x-model="person.email"
                        >
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="mt-3" x-show="subAuthors.length === 0">
        <button type="button" class="btn-secondary" @click="add()">Add a sub author</button>
    </div>

    <x-input-error :messages="$errors->get('sub_authors')" class="mt-2" />
    <x-input-error :messages="$errors->get('sub_authors.*.name')" class="mt-2" />
    <x-input-error :messages="$errors->get('sub_authors.*.email')" class="mt-2" />
</div>
