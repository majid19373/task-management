<?php

namespace Src\Application\Commands\Board;

use Src\Application\Commands\CommandInterface;

final readonly class CreateBoardCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public string $name,
        public ?string $description = null,
    )
    {}
}
