<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use Src\Application\Queries\Board\PaginateBoardQuery;

final class PaginateBoardFilterRequest extends FormRequest
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

    public function makeDTO(): PaginateBoardQuery
    {
        return new PaginateBoardQuery(
            page: $this->page ?? 1,
            perPage: $this->per_page ?? 10,
        );
    }
}
