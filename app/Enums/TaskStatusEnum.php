<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, TaskStatusEnum::cases());
    }
}
