<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('slug')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug((string) $this->input('slug')),
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
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'min:2',
                'max:60',
                'alpha_dash',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'string', 'max:500', 'url'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
