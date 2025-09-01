<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Src\Application\Queries\Task\ListTaskQuery;
use Illuminate\Foundation\Http\FormRequest;

final class ListTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => ['required'],
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
        ];
    }

    public function makeDTO(): ListTaskQuery
    {
        return new ListTaskQuery(
            boardId: $this->board_id,
            priority: $this->priority,
            status: $this->status,
        );
    }
}
