<?php

namespace App\Entities;


use App\ValueObjects\Board\{BoardName, BoardDescription};

final class Board
{
    private int $id;
    private int $userId;
    private BoardName $name;
    private ?BoardDescription $description;

    public function __construct(BoardName $name, int $userId, ?BoardDescription $description = null)
    {
        $this->name = $name;
        $this->userId = $userId;
        $this->description = $description;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int { return $this->id; }
    public function getName(): BoardName { return $this->name; }
    public function getUserId(): int { return $this->userId; }
    public function getDescription(): ?BoardDescription { return $this->description; }
}
