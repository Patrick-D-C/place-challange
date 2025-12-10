<?php

namespace App\Http\Requests;

use App\Services\PlaceService;
use Illuminate\Foundation\Http\FormRequest;

class ListPlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string', 'max:255'],
            'city'     => ['sometimes', 'string', 'max:255'],
            'state'    => ['sometimes', 'string', 'size:2'],
            'sort'     => [
                'sometimes',
                'string',
                function (string $attribute, mixed $value, callable $fail) {
                    $column = ltrim((string) $value, '-');

                    if (!in_array($column, PlaceService::SORTABLE_COLUMNS, true)) {
                        $fail('The selected sort is invalid.');
                    }
                },
            ],
            'page'     => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
