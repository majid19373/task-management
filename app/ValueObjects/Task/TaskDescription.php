<?php

namespace App\ValueObjects\Task;

use InvalidArgumentException;

final class TaskDescription
{
    private ?string $description;

    public function __construct(?string $description)
    {
        if ($description && strlen($description) > 500) {
            throw new InvalidArgumentException("Task description must be less than 500 characters.");
        }

        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
