<?php

namespace App\Entities;

use InvalidArgumentException;

final class Board
{
    private int $id;
    private int $userId;
    private string $name;
    private ?string $description;

    public function __construct(int $id, string $name, int $userId, ?string $description = null)
    {
        $this->id = $id;
        $this->setName($name);
        $this->userId = $userId;
        $this->setDescription($description);
    }

    public function setName(string $name): void
    {
        $length = strlen($name);
        if ($length < 3 || $length > 50) {
            throw new InvalidArgumentException("Board name must be between 3 and 50 characters.");
        }

        $this->name = $name;
    }

    public function setDescription(?string $description): void
    {
        if ($description && strlen($description) > 200) {
            throw new InvalidArgumentException("Board description must be less than 200 characters.");
        }

        $this->description = $description;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getUserId(): int { return $this->userId; }
    public function getDescription(): ?string { return $this->description; }
}
