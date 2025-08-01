<?php

namespace App\Http\Resources\Task;

use App\Entities\Task;
use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;

final class TaskEditResource
{
    public const array JSON_STRUCTURE = [
        'task' => TaskResource::JSON_STRUCTURE,
        'statuses',
        'priorities',
    ];

    public static function toArray(Task $task): array
    {
        return [
            'task' => TaskResource::toArray($task),
            'statuses' => TaskStatusEnum::toArray(),
            'priorities' => TaskPriorityEnum::toArray(),
        ];
    }
}
