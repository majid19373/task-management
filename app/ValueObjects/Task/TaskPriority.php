<?php

namespace App\ValueObjects\Task;

use InvalidArgumentException;

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

    public static function toCase(string $input): TaskPriority
    {
        return match ($input) {
            'low' => TaskPriority::LOW,
            'medium' => TaskPriority::MEDIUM,
            'high' => TaskPriority::HIGH,
            'critical' => TaskPriority::CRITICAL,
            default => throw new InvalidArgumentException("Invalid task priority: {$input}"),
        };
    }

    public static function validate(?string $input): void
    {
        if ($input && !in_array($input, TaskPriority::toArray())) {
            throw new InvalidArgumentException('Status task is not valid.');
        }
    }
}
