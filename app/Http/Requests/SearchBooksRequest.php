<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchBooksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required|string|max:100'
        ];
    }
}
