<?php

namespace App\ValueObjects\Subtask;

use InvalidArgumentException;

final class SubtaskTitle
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

    public function value(): string
    {
        return $this->title;
    }
}
