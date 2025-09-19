<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use Src\Application\Queries\Board\ListBoardQuery;

final class BoardListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
        ];
    }

    public function makeDTO(): ListBoardQuery
    {
        return new ListBoardQuery($this->user_id);
    }
}
