<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Subtask;

use Src\application\DTO\Subtask\NewSubtask;
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

    public function makeDTO(): NewSubtask
    {
        return new NewSubtask(
            title: $this->title,
            taskId: $this->task_id,
            description: $this->description,
            deadline: $this->deadline,
        );
    }
}
