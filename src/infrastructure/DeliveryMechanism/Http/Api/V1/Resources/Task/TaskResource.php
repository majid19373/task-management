<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Task;

use Src\domain\Entities\Task\Task;
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
            'board_id' => $task->getBoard()->getId(),
            'title' => $task->getTitle()->value(),
            'description' => $task->getDescription()?->value(),
            'status' => $task->getStatus()->value,
            'priority' => $task->getPriority()->value,
            'deadline' => $task->getDeadline()?->value(),
        ];
    }

    public static function toArrayList(array $tasks): Collection
    {
        return collect($tasks)->map(function ($task) {
            return TaskResource::toArray($task);
        });
    }
}
