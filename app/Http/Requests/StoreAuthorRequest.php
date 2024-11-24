<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:6',
                'max:50',
                'regex:/[A-Z]/',  // Must contain an uppercase letter
                'regex:/[a-z]/',  // Must contain a lowercase letter
                'regex:/[@$!%*#?&]/', // Must contain a special character
            ],
        ];
    }
}
