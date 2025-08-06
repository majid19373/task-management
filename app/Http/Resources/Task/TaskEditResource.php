<?php

namespace App\Http\Resources\Task;

use App\Entities\Task;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;

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
            'statuses' => TaskStatus::toArray(),
            'priorities' => TaskPriority::toArray(),
        ];
    }
}
