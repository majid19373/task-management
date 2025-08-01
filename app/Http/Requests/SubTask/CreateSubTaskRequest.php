<?php

namespace App\Http\Requests\SubTask;

use Illuminate\Foundation\Http\FormRequest;

final class CreateSubTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => ['required'],
            'title' => ['required', 'string'],
            'task_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
        ];
    }
}
