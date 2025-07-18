<?php

namespace App\Http\Resources\Task;

use App\Entities\Task;
use Illuminate\Support\Collection;

final class TaskResource
{
    public const JSON_STRUCTURE = [
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
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'priority' => $task->getPriority(),
            'deadline' => $task->getDeadline(),
        ];
    }

    public static function toArrayList(Collection $tasks): Collection
    {
        return $tasks->map(function ($task) {
            return TaskResource::toArray($task);
        });
    }
}
