<?php

namespace App\ValueObjects\Subtask;

use InvalidArgumentException;

enum SubtaskStatus: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case BLOCKED = 'blocked';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, SubtaskStatus::cases());
    }

    public static function toCase(string $input): SubtaskStatus
    {
        return match ($input) {
            'not_started' => SubtaskStatus::NOT_STARTED,
            'in_progress' => SubtaskStatus::IN_PROGRESS,
            'completed' => SubtaskStatus::COMPLETED,
            'blocked' => SubtaskStatus::BLOCKED,
            default => throw new InvalidArgumentException("Invalid task status: {$input}"),
        };
    }
}
