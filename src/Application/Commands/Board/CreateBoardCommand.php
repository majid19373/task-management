<?php

namespace Src\Application\Commands\Board;

final readonly class CreateBoardCommand
{
    public function __construct(
        public int $userId,
        public string $name,
        public ?string $description = null,
    )
    {}
}
