<?php

namespace App\ValueObjects\Board;

use DomainException;

final class BoardName
{
    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function createNew(string $name): BoardName
    {
        $length = strlen($name);
        if ($length < 3 || $length > 50) {
            throw new DomainException("Board name must be between 3 and 50 characters.");
        }
        return new self($name);
    }

    public static function reconstitute(string $name): BoardName
    {
        return new self($name);
    }

    public function value(): string
    {
        return $this->name;
    }
}
