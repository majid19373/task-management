<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Subtask;

use Src\Application\Commands\Subtask\AddSubtaskCommand;
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
            'task_id' => ['nullable'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
        ];
    }

    public function makeDTO(): AddSubtaskCommand
    {
        return new AddSubtaskCommand(
            title: $this->title,
            taskId: $this->task_id,
            description: $this->description,
            deadline: $this->deadline,
        );
    }
}
