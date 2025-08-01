<?php

namespace App\Http\Requests\Task;

use App\DTO\Task\TaskFilterDTO;
use Carbon\Carbon;
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
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
        ];
    }

    public function makeDTO(): TaskFilterDTO
    {
        return new TaskFilterDTO(
            boardId: $this->board_id,
            isPaginated:$this->is_paginated == 1 || $this->is_paginated === null,
            perPage: $this->per_page ?? 10,
            priority: $this->priority,
            status: $this->status,
        );
    }
}
