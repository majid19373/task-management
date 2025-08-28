<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Src\application\DTO\Task\TaskFilter;
use Illuminate\Foundation\Http\FormRequest;

final class FilterTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => ['required'],
            'is_paginated' => ['nullable'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
        ];
    }

    public function makeDTO(): TaskFilter
    {
        return new TaskFilter(
            boardId: $this->board_id,
            isPaginated: $this->page > 0,
            page: $this->page ?? 1,
            perPage: $this->per_page ?? 10,
            priority: $this->priority,
            status: $this->status,
        );
    }
}
