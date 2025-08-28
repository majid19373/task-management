<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board;

use Src\application\DTO\Board\NewBoard;
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

    public function makeDTO(): NewBoard
    {
        return new NewBoard(
            userId: $this->user_id,
            name: $this->name,
            description: $this->description,
        );
    }
}
