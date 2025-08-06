<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

final class PriorityTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'priority' => ['required'],
        ];
    }
}
