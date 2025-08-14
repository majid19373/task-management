<?php

namespace App\ValueObjects\Subtask;

use DomainException;

final class SubtaskDescription
{
    private string $description;

    public function __construct(?string $description)
    {
        $this->description = $description;
    }

    public static function createNew(string $description): SubtaskDescription
    {
        if (strlen($description) > 500) {
            throw new DomainException("Subtask description must be less than 500 characters.");
        }
        return new self($description);
    }

    public static function reconstitute(string $description): SubtaskDescription
    {
        return new self($description);
    }

    public function value(): ?string
    {
        return $this->description;
    }
}
