<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Task;

use Src\Domain\Subtask\Subtask;
use Src\Domain\Task\Task;
use Illuminate\Support\Collection;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Subtask\SubtaskResource;

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
        'subtasks',
    ];

    public static function toArray(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'board_id' => $task->getBoardId(),
            'title' => $task->getTitle()->value(),
            'description' => $task->getDescription()?->value(),
            'status' => $task->getStatus()->value,
            'priority' => $task->getPriority()->value,
            'deadline' => $task->getDeadline()?->value(),
            'subtasks' => SubtaskResource::toArrayList($task->getSubtasks()),
        ];
    }

    public static function toArrayList(array $tasks): Collection
    {
        return collect($tasks)->map(function ($task) {
            return TaskResource::toArray($task);
        });
    }
}
