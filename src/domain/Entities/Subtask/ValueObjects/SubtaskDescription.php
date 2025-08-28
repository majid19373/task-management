<?php

namespace Src\domain\Entities\Subtask\ValueObjects;

use DomainException;

final class SubtaskDescription
{
    private string $value;

    public function __construct(?string $value)
    {
        if (strlen($value) > 500) {
            throw new DomainException("Subtask description must be less than 500 characters.");
        }
        $this->value = $value;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
