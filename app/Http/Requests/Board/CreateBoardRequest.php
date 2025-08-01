<?php

namespace App\Http\Requests\Board;

use App\DTO\Board\NewBoardDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CreateBoardRequest extends FormRequest
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
            'user_id' => ['required'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function makeDTO(): NewBoardDTO
    {
        return new NewBoardDTO(
            userId: $this->user_id,
            name: $this->name,
            description: $this->description,
        );
    }
}
