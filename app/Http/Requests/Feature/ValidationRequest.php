<?php

namespace App\Http\Requests\Feature;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'age' => ['required', 'integer', 'min:18', 'max:120'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('Please enter your full name.'),
            'email.required' => __('We need your email address.'),
            'email.email' => __('That doesn\'t look like a valid email.'),
            'age.required' => __('Please enter your age.'),
            'age.min' => __('You must be at least 18 years old.'),
            'age.max' => __('Please enter a valid age.'),
            'website.url' => 'Please enter a valid URL (e.g., https://example.com).',
        ];
    }
}