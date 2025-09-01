<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board;

use Src\Application\Commands\Board\CreateBoardCommand;
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

    public function makeDTO(): CreateBoardCommand
    {
        return new CreateBoardCommand(
            userId: $this->user_id,
            name: $this->name,
            description: $this->description,
        );
    }
}
