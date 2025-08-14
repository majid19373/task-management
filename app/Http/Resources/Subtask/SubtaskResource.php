<?php

namespace App\Http\Resources\Subtask;

use App\Entities\Subtask;
use Illuminate\Support\Collection;

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
            'task_id' => $subtask->getTaskId(),
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
