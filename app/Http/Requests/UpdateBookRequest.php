<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|min:2|max:100',
            'description' => 'nullable|string|min:5|max:500',
            'published_at' => 'nullable|date',
            'bio' => 'nullable|string|min:5|max:500',
            'cover' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ];
    }
}
