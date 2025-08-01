<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriorityEnum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class DeadlineTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'deadline' => ['required', 'date'],
        ];
    }
}
