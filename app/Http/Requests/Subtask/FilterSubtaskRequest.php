<?php

namespace App\Http\Requests\Subtask;

use App\DTO\Subtask\SubtaskFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

final class FilterSubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id' => ['required'],
            'is_paginated' => ['nullable'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function makeDTO(): SubtaskFilterDTO
    {
        return new SubtaskFilterDTO(
            taskId: $this->task_id,
            isPaginated:$this->is_paginated == 1 || $this->is_paginated === null,
            perPage: $this->per_page ?? 10,
        );
    }
}
