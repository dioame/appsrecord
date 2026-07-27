<?php

namespace App\Support;

trait NormalizesTags
{
    /**
     * @return list<string>
     */
    protected function normalizedTags(): array
    {
        return collect($this->input('tags', []))
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->unique(fn (string $tag) => mb_strtolower($tag))
            ->values()
            ->take(12)
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    protected function tagRules(): array
    {
        return [
            'tags' => ['nullable', 'array', 'max:12'],
            'tags.*' => ['required', 'string', 'max:40'],
        ];
    }
}
