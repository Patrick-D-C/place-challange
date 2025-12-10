<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['sometimes', 'required', 'string', 'max:255'],
            'slug'  => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('places', 'slug')->ignore($this->route('place')),
            ],
            'city'  => ['sometimes', 'required', 'string', 'max:255'],
            'state' => ['sometimes', 'required', 'string', 'size:2'],
        ];
    }
}
