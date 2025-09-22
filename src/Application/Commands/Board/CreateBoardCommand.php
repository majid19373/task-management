<?php

namespace Src\Application\Commands\Board;

use Src\Application\CommandHandlers\Board\CreateBoardCommandHandler;

/**
 * @see CreateBoardCommandHandler
 */
final readonly class CreateBoardCommand
{
    public function __construct(
        public int $userId,
        public string $name,
        public ?string $description = null,
    )
    {}
}
