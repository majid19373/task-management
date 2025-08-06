<?php

namespace App\ValueObjects\Task;

use InvalidArgumentException;

enum TaskStatus: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case BLOCKED = 'blocked';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, TaskStatus::cases());
    }

    public static function toCase(string $input): TaskStatus
    {
        return match ($input) {
            'not_started' => TaskStatus::NOT_STARTED,
            'in_progress' => TaskStatus::IN_PROGRESS,
            'completed' => TaskStatus::COMPLETED,
            'blocked' => TaskStatus::BLOCKED,
            default => throw new InvalidArgumentException("Invalid task status: {$input}"),
        };
    }

    public static function validate(?string $input): void
    {
        if ($input && !in_array($input, TaskStatus::toArray())) {
            throw new InvalidArgumentException('Status task is not valid.');
        }
    }
}
