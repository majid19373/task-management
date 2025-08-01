<?php

namespace App\Rules\Task;

use App\Enums\TaskPriorityEnum;
use InvalidArgumentException;

final class CheckPriority
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(?string $priority): void
    {
        if ($priority && !in_array($priority, TaskPriorityEnum::toArray())) {
            throw new InvalidArgumentException(
                message: 'Priority task is not valid.',
            );
        }
    }
}
