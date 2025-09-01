<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Subtask;

use Src\Domain\Subtask\Subtask;
use Doctrine\Common\Collections\Collection;

final class SubtaskResource
{
    public const array JSON_STRUCTURE = [
        'id',
        'task_id',
        'title',
        'description',
        'status',
    ];

    public static function toArray(Subtask $subtask): array
    {
        return [
            'id' => $subtask->getId(),
            'task' => $subtask->getTask()->getId(),
            'title' => $subtask->getTitle()->value(),
            'description' => $subtask->getDescription()?->value(),
            'status' => $subtask->getStatus()->value,
        ];
    }

    public static function toArrayList(Collection $subtasks): Collection
    {
        return $subtasks->map(function ($subtask) {
            return SubtaskResource::toArray($subtask);
        });
    }
}
