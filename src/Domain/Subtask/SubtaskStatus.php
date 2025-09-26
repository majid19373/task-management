<?php

namespace Src\Domain\Subtask;

use DomainException;

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

    public static function validate(?string $input): void
    {
        if (!SubtaskStatus::tryFrom($input)) {
            throw new DomainException('Subtask status is not valid.');
        }
    }
}
