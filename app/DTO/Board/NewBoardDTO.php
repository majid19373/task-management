<?php

namespace App\DTO\Board;

final class NewBoardDTO
{
    public function __construct(
        public int $userId,
        public string $name,
        public ?string $description = null,
    )
    {}
}
