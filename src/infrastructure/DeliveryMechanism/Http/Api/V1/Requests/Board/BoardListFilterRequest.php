<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board;

use Src\application\DTO\Board\BoardFilter;
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
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function makeDTO(): BoardFilter
    {
        return new BoardFilter(
            isPaginated: $this->page > 0,
            page: $this->page ?? 1,
            perPage: $this->per_page ?? 10,
        );
    }
}
