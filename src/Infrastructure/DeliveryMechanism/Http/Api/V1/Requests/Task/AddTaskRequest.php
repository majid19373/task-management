<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Src\Application\Commands\Task\AddTaskCommand;
use Illuminate\Foundation\Http\FormRequest;

final class AddTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => ['required'],
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
        ];
    }

    public function makeDTO(): AddTaskCommand
    {
        return new AddTaskCommand(
            boardId: $this->board_id,
            title: $this->title,
            description: $this->description,
            deadline: $this->deadline,
        );
    }
}
