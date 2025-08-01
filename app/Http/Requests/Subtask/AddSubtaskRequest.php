<?php

namespace App\Http\Requests\Subtask;

use App\DTO\Subtask\NewSubtaskDTO;
use Illuminate\Foundation\Http\FormRequest;

final class AddSubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'task_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
        ];
    }

    public function makeDTO(): NewSubtaskDTO
    {
        return new NewSubtaskDTO(
            title: $this->title,
            taskId: $this->task_id,
            description: $this->description,
            deadline: $this->deadline,
        );
    }
}
