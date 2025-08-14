<?php

namespace App\Http\Requests\Board;

use App\DTO\Board\BoardFilter;
use Illuminate\Foundation\Http\FormRequest;

final class BoardListFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_paginated' => ['nullable', 'in:0,1'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function makeDTO(): BoardFilter
    {
        return new BoardFilter(
            isPaginated: $this->is_paginated == 1 || $this->is_paginated === null,
            perPage: $this->per_page ?? 10,
        );
    }
}
