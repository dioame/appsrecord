<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('slug')) {
            $this->merge([
                'slug' => Str::slug((string) $this->input('slug')),
            ]);
        }

        if ($this->filled('website')) {
            $website = trim((string) $this->input('website'));
            if ($website !== '' && ! preg_match('#^https?://#i', $website)) {
                $website = 'https://'.$website;
            }
            $this->merge(['website' => $website ?: null]);
        } else {
            $this->merge(['website' => null]);
        }

        if ($this->input('_section') === 'cv' || $this->hasAny(['headline', 'location', 'skills', 'experience', 'education'])) {
            $this->merge([
                'headline' => $this->nullableTrimmed('headline'),
                'location' => $this->nullableTrimmed('location'),
                'skills' => $this->normalizedSkills(),
                'experience' => $this->normalizedExperience(),
                'education' => $this->normalizedEducation(),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isCvOnly = $this->input('_section') === 'cv';

        return [
            'name' => [$isCvOnly ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => [
                $isCvOnly ? 'sometimes' : 'required',
                'string',
                'min:2',
                'max:60',
                'alpha_dash',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'string', 'max:500', 'url'],
            'email' => [
                $isCvOnly ? 'sometimes' : 'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'headline' => ['nullable', 'string', 'max:160'],
            'location' => ['nullable', 'string', 'max:120'],
            'skills' => ['nullable', 'array', 'max:30'],
            'skills.*' => ['required', 'string', 'max:60'],
            'experience' => ['nullable', 'array', 'max:20'],
            'experience.*.title' => ['nullable', 'string', 'max:120'],
            'experience.*.company' => ['nullable', 'string', 'max:120'],
            'experience.*.period' => ['nullable', 'string', 'max:80'],
            'experience.*.description' => ['nullable', 'string', 'max:500'],
            'education' => ['nullable', 'array', 'max:15'],
            'education.*.school' => ['nullable', 'string', 'max:120'],
            'education.*.degree' => ['nullable', 'string', 'max:120'],
            'education.*.period' => ['nullable', 'string', 'max:80'],
            'education.*.description' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function nullableTrimmed(string $key): ?string
    {
        $value = trim((string) $this->input($key, ''));

        return $value !== '' ? $value : null;
    }

    /**
     * @return list<string>
     */
    protected function normalizedSkills(): array
    {
        $raw = $this->input('skills');

        if (is_string($raw)) {
            $raw = preg_split('/[\n,]+/', $raw) ?: [];
        }

        return collect(is_array($raw) ? $raw : [])
            ->map(fn ($skill) => is_string($skill) ? trim($skill) : '')
            ->filter()
            ->unique(fn (string $skill) => mb_strtolower($skill))
            ->values()
            ->take(30)
            ->all();
    }

    /**
     * @return list<array{title: string, company: string, period: string, description: string}>
     */
    protected function normalizedExperience(): array
    {
        return collect($this->input('experience', []))
            ->filter(fn ($entry) => is_array($entry))
            ->map(fn (array $entry) => [
                'title' => trim((string) ($entry['title'] ?? '')),
                'company' => trim((string) ($entry['company'] ?? '')),
                'period' => trim((string) ($entry['period'] ?? '')),
                'description' => trim((string) ($entry['description'] ?? '')),
            ])
            ->filter(fn (array $entry) => $entry['title'] !== '' || $entry['company'] !== '')
            ->values()
            ->take(20)
            ->all();
    }

    /**
     * @return list<array{school: string, degree: string, period: string, description: string}>
     */
    protected function normalizedEducation(): array
    {
        return collect($this->input('education', []))
            ->filter(fn ($entry) => is_array($entry))
            ->map(fn (array $entry) => [
                'school' => trim((string) ($entry['school'] ?? '')),
                'degree' => trim((string) ($entry['degree'] ?? '')),
                'period' => trim((string) ($entry['period'] ?? '')),
                'description' => trim((string) ($entry['description'] ?? '')),
            ])
            ->filter(fn (array $entry) => $entry['school'] !== '' || $entry['degree'] !== '')
            ->values()
            ->take(15)
            ->all();
    }
}
