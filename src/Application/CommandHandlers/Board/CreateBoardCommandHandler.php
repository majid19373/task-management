<?php

namespace Src\Application\CommandHandlers\Board;

use Src\Application\Commands\Board\CreateBoardCommand;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardDescription;
use Src\Domain\Board\BoardName;
use Src\Application\Repositories\BoardRepositoryInterface;

final readonly class CreateBoardCommandHandler
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    public function handle(CreateBoardCommand $command): void
    {
        $name = new BoardName($command->name);
        $existsByUserIdAndName = $this->boardRepository->existsByUserIdAndName($command->userId, $name);
        $board = new Board(
            id: $this->boardRepository->getNextIdentity(),
            existsByUserIdAndName: $existsByUserIdAndName,
            name: $name,
            userId: (int)$command->userId,
            description: $command->description ? new BoardDescription($command->description) : null,
        );
        $this->boardRepository->store($board);
    }
}
