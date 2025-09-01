<?php

namespace Src\Domain\Board;

use Doctrine\ORM\Mapping\{Column, Embeddable};
use DomainException;

#[Embeddable]
final class BoardDescription
{
    #[Column(name: "description", type: "string", length: 200)]
    public string $value;

    public function __construct(string $value)
    {
        if (strlen($value) > 200) {
            throw new DomainException("Board description must be less than 200 characters.");
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
