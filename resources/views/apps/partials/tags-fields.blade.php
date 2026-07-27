@php
    $initialTags = old('tags', isset($appListing) ? $appListing->tags->pluck('name')->all() : []);
@endphp
<div
    x-data="{
        tags: {{ \Illuminate\Support\Js::from(array_values($initialTags)) }},
        draft: '',
        max: 12,
        addTag() {
            const value = this.draft.trim().replace(/\s+/g, ' ');
            if (!value || this.tags.length >= this.max) return;
            const exists = this.tags.some((tag) => tag.toLowerCase() === value.toLowerCase());
            if (exists) { this.draft = ''; return; }
            this.tags.push(value.slice(0, 40));
            this.draft = '';
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
    }"
>
    <label for="tag_draft" class="form-label">Tags <span class="font-normal text-[#86868B]">(optional)</span></label>
    <div class="mt-2 flex flex-wrap gap-2" x-show="tags.length" x-cloak>
        <template x-for="(tag, index) in tags" :key="tag + '-' + index">
            <span class="inline-flex items-center gap-1 rounded-full bg-[#F5F5F7] px-3 py-1 text-[13px] font-medium text-[#1D1D1F]">
                <span x-text="tag"></span>
                <input type="hidden" :name="'tags[' + index + ']'" :value="tag">
                <button type="button" class="rounded-full p-0.5 text-[#86868B] hover:bg-white hover:text-[#1D1D1F]" @click="removeTag(index)" aria-label="Remove tag">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </span>
        </template>
    </div>
    <div class="mt-2 flex gap-2">
        <input
            id="tag_draft"
            type="text"
            class="form-input"
            x-model="draft"
            @keydown.enter.prevent="addTag()"
            @keydown.comma.prevent="addTag()"
            maxlength="40"
            placeholder="e.g. offline, finance, AI"
            :disabled="tags.length >= max"
        >
        <button type="button" class="btn-ghost shrink-0 !px-4" @click="addTag()" :disabled="tags.length >= max">Add</button>
    </div>
    <p class="mt-2 text-xs text-[#71717A]">Add one or more tags. Press Enter or Add. Up to 12 tags — used for search filters.</p>
    <x-input-error :messages="$errors->get('tags')" class="mt-2" />
    <x-input-error :messages="$errors->get('tags.*')" class="mt-2" />
</div>
