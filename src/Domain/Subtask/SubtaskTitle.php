<?php

namespace Src\Domain\Subtask;

use DomainException;

final class SubtaskTitle
{
    private string $value;

    public function __construct(string $value)
    {
        $length = strlen($value);
        if ($length < 5 || $length > 100) {
            throw new DomainException("Subtask title must be between 5 and 100 characters.");
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
