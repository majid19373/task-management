<?php

namespace App\Enums;

enum TaskPriorityEnum: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, TaskPriorityEnum::cases());
    }
}
