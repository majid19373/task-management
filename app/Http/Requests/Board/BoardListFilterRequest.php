<?php

namespace App\Http\Requests\Board;

use App\DTO\Board\BoardFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

final class BoardListFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_paginated' => ['nullable', 'in:0,1'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function makeDTO(): BoardFilterDTO
    {
        return new BoardFilterDTO(
            isPaginated: $this->is_paginated == 1 || $this->is_paginated === null,
            perPage: $this->per_page ?? 10,
        );
    }
}
