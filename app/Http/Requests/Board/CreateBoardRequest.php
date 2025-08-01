<?php

namespace App\Http\Requests\Board;

use App\DTO\Board\NewBoardDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CreateBoardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
