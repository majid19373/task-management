<?php

namespace App\ValueObjects\Task;

use DomainException;

final class TaskDescription
{
    private string $description;

    private function __construct(string $description)
    {
        $this->description = $description;
    }

    public static function createNew(string $description): TaskDescription
    {
        if (strlen($description) > 500) {
            throw new DomainException("Task description must be less than 500 characters.");
        }
        return new self($description);
    }

    public static function reconstitute(string $description): TaskDescription
    {
        return new self($description);
    }

    public function value(): ?string
    {
        return $this->description;
    }
}
