<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'slug'  => ['nullable', 'string', 'max:255', 'unique:places,slug'],
            'city'  => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2'],
        ];
    }
}
