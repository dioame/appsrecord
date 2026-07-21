<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'author' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:5000'],
            'link' => ['nullable', 'url', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'platform' => ['required', 'in:mobile,web,desktop'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.max' => 'You can upload a maximum of 3 screenshots.',
        ];
    }
}
