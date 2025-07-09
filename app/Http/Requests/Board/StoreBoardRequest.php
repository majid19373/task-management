<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBoardRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('boards')->where(function ($query) {
                    return $query->where('user_id', $this->user_id);
                }),
            ],
            'description' => ['nullable', 'string', 'max:200'],
        ];
    }
}
