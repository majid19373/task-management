<?php

namespace App\ValueObjects\Board;

use DomainException;

final class BoardDescription
{
    private string $description;

    private function __construct(string $description)
    {
        $this->description = $description;
    }

    public static function createNew(string $description): BoardDescription
    {
        if (strlen($description) > 200) {
            throw new DomainException("Board description must be less than 200 characters.");
        }
        return new self($description);
    }

    public static function reconstitute(string $description): BoardDescription
    {
        return new self($description);
    }

    public function value(): ?string
    {
        return $this->description;
    }
}
