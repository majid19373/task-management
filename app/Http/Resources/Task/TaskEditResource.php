<?php

namespace App\Http\Resources\Task;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskEditResource extends JsonResource
{
    public const JSON_STRUCTURE = [
        'task' => TaskResource::JSON_STRUCTURE,
        'statuses',
        'priorities',
    ];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'task' => new TaskResource($this->resource),
            'statuses' => TaskStatusEnum::toArray(),
            'priorities' => TaskPriorityEnum::toArray(),
        ];
    }
}
