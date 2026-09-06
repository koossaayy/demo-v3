<?php

namespace App\Http\Requests\Feature;

use Illuminate\Foundation\Http\FormRequest;

class DottedKeysRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', __('email'), 'max:255'],
            'address.street' => ['required', 'string', 'max:255'],
            'address.city' => ['required', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user.name' => __('name'),
            'user.email' => __('email'),
            'address.street' => __('street'),
            'address.city' => __('city'),
        ];
    }
}