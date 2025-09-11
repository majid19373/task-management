<?php

namespace Src\Domain\Subtask;

use Doctrine\ORM\Mapping\{Column, Embeddable};
use DomainException;

#[Embeddable]
final class SubtaskTitle
{
    #[Column(name: "title", type: "string", length: 100)]
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
