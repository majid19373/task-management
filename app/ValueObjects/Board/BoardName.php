<?php

namespace App\ValueObjects\Board;

use InvalidArgumentException;

final class BoardName
{
    private string $name;

    public function __construct(string $name)
    {
        $length = strlen($name);
        if ($length < 3 || $length > 50) {
            throw new InvalidArgumentException("Board name must be between 3 and 50 characters.");
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
