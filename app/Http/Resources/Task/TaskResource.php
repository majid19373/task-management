<?php

namespace App\Http\Resources\Task;

use App\Entities\Task;
use Illuminate\Support\Collection;

final class TaskResource
{
    public const array JSON_STRUCTURE = [
        'id',
        'board_id',
        'title',
        'description',
        'status',
        'priority',
        'deadline',
    ];

    public static function toArray(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'board_id' => $task->getBoardId(),
            'title' => $task->getTitle()->value(),
            'description' => $task->getDescription()?->value(),
            'status' => $task->getStatus()->value(),
            'priority' => $task->getPriority()->value(),
            'deadline' => $task->getDeadline()?->value(),
        ];
    }

    public static function toArrayList(Collection $tasks): Collection
    {
        return $tasks->map(function ($task) {
            return TaskResource::toArray($task);
        });
    }
}
