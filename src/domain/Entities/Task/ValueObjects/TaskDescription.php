<?php

namespace Src\domain\Entities\Task\ValueObjects;

use Doctrine\ORM\Mapping\{Column, Embeddable};
use DomainException;

#[Embeddable]
final class TaskDescription
{
    #[Column(name: "description", type: "string", length: 500)]
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) > 500) {
            throw new DomainException("Task description must be less than 500 characters.");
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
