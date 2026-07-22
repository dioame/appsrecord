<?php

namespace App\Support;

trait NormalizesSubAuthors
{
    /**
     * @return list<array{name: string, email: ?string}>
     */
    protected function normalizedSubAuthors(): array
    {
        return collect($this->input('sub_authors', []))
            ->filter(fn ($entry) => is_array($entry))
            ->map(fn (array $entry) => [
                'name' => trim((string) ($entry['name'] ?? '')),
                'email' => trim((string) ($entry['email'] ?? '')),
            ])
            ->filter(fn (array $entry) => $entry['name'] !== '')
            ->map(fn (array $entry) => [
                'name' => $entry['name'],
                'email' => $entry['email'] !== '' ? mb_strtolower($entry['email']) : null,
            ])
            ->values()
            ->take(20)
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    protected function subAuthorRules(): array
    {
        return [
            'sub_authors' => ['nullable', 'array', 'max:20'],
            'sub_authors.*.name' => ['nullable', 'string', 'max:120'],
            'sub_authors.*.email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
