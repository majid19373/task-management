<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Src\Application\Queries\Task\PaginateTaskQuery;
use Illuminate\Foundation\Http\FormRequest;

final class PaginateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => ['required'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
        ];
    }

    public function makeDTO(): PaginateTaskQuery
    {
        return new PaginateTaskQuery(
            boardId: $this->board_id,
            page: $this->page ?? 1,
            perPage: $this->per_page ?? 10,
            priority: $this->priority,
            status: $this->status,
        );
    }
}
