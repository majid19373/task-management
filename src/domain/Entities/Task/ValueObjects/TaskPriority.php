<?php

namespace Src\domain\Entities\Task\ValueObjects;

use DomainException;

enum TaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, TaskPriority::cases());
    }

    public static function validate(string $input): TaskPriority
    {
        $priority = TaskPriority::tryFrom($input);
        if (!$priority) {
            throw new DomainException('Status task is not valid.');
        }
        return $priority;
    }
}
