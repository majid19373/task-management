<?php

namespace App\ValueObjects\Board;

use InvalidArgumentException;

final class BoardDescription
{
    private ?string $description;

    public function __construct(?string $description)
    {
        if ($description && strlen($description) > 200) {
            throw new InvalidArgumentException("Board description must be less than 200 characters.");
        }

        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
