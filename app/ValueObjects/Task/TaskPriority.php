<?php

namespace App\ValueObjects\Task;

use App\Enums\TaskPriorityEnum;
use InvalidArgumentException;

final class TaskPriority
{
    private string $priority = TaskPriorityEnum::MEDIUM->value;

    public function __construct(string $priority)
    {
        if (!in_array($priority, TaskPriorityEnum::toArray())) {
            throw new InvalidArgumentException("Priority is not a valid Task priority.");
        }

        $this->priority = $priority;
    }

    public function value(): string
    {
        return $this->priority;
    }
}
