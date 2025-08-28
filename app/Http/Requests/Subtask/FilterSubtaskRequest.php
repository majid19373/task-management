<?php

namespace App\Http\Requests\Subtask;

use App\DTO\Subtask\SubtaskFilter;
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
            'is_paginated' => ['nullable'],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function makeDTO(): SubtaskFilter
    {
        return new SubtaskFilter(
            taskId: $this->task_id,
            isPaginated: $this->page > 0,
            page: $this->page ?? 1,
            perPage: $this->per_page ?? 10,
        );
    }
}
