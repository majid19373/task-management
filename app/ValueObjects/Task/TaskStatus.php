<?php

namespace App\ValueObjects\Task;

use App\Enums\TaskStatusEnum;
use InvalidArgumentException;

final class TaskStatus
{
    private string $status = TaskStatusEnum::NOT_STARTED->value;

    public function __construct(string $status)
    {
        if (!in_array($status, TaskStatusEnum::toArray())) {
            throw new InvalidArgumentException("Staus is not a valid Task status.");
        }

        $this->status = $status;
    }

    public function value(): string
    {
        return $this->status;
    }
}
