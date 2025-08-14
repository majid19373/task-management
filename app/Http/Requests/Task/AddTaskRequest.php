<?php

namespace App\Http\Requests\Task;

use App\DTO\Task\NewTask;
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

    public function makeDTO(): NewTask
    {
        return new NewTask(
            boardId: $this->board_id,
            title: $this->title,
            description: $this->description,
            deadline: $this->deadline,
        );
    }
}
