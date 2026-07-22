<?php

namespace App\Http\Requests;

use App\Models\AppListing;
use App\Support\NormalizesSubAuthors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppListingRequest extends FormRequest
{
    use NormalizesSubAuthors;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sub_authors' => $this->normalizedSubAuthors(),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'author' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:5000'],
            'link' => ['nullable', 'url', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'platform' => ['required', Rule::in(AppListing::PLATFORMS)],
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            ...$this->subAuthorRules(),
        ];
    }

    public function messages(): array
    {
        return [
            'images.max' => 'You can upload a maximum of 3 screenshots.',
            'logo.required' => 'Please upload an app logo.',
            'sub_authors.max' => 'You can add up to 20 sub authors.',
        ];
    }
}
