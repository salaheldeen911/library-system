<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:100',
            'description' => 'required|string|min:5|max:500',
            'published_at' => 'required|date',
            'bio' => 'required|string|min:5|max:500',
            'cover' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ];
    }
}
