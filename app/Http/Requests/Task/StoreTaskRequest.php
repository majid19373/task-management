<?php

namespace App\Http\Requests\Task;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

final class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'board_id' => ['required', 'exists:boards,id'],
            'title' => ['required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'deadline' => [
                'nullable',
                'date',
                'after_or_equal:' . Carbon::now(),
            ],
        ];
    }
}
