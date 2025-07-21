<?php

namespace App\ValueObjects\Task;

use InvalidArgumentException;

final class TaskTitle
{
    private string $title;

    public function __construct(string $title)
    {
        $length = strlen($title);
        if ($length < 5 || $length > 100) {
            throw new InvalidArgumentException("Task title must be between 5 and 100 characters.");
        }

        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
