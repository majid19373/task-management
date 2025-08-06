<?php

namespace App\ValueObjects\Subtask;

use InvalidArgumentException;

enum SubtaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, SubtaskPriority::cases());
    }

    public static function toCase(string $input): SubtaskPriority
    {
        return match ($input) {
            'low' => SubtaskPriority::LOW,
            'medium' => SubtaskPriority::MEDIUM,
            'high' => SubtaskPriority::HIGH,
            'critical' => SubtaskPriority::CRITICAL,
            default => throw new InvalidArgumentException("Invalid task priority: {$input}"),
        };
    }
}
