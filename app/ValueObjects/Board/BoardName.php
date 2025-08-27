<?php

namespace App\ValueObjects\Board;

use Doctrine\ORM\Mapping\{Column, Embeddable};
use DomainException;

#[Embeddable]
final class BoardName
{
    #[Column(name: "name", type: "string", length: 50)]
    private string $value;

    public function __construct(string $value)
    {
        $length = strlen($value);
        if ($length < 3 || $length > 50) {
            throw new DomainException("Board name must be between 3 and 50 characters.");
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
