<?php

namespace App\ValueObjects\Task;

use DomainException;

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

    public static function validate(?string $input): void
    {
        if (!TaskStatus::tryFrom($input)) {
            throw new DomainException('Status task is not valid.');
        }
    }
}
