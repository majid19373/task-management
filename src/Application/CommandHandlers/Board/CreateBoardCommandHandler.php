<?php

namespace Src\Application\CommandHandlers\Board;

use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\Board\CreateBoardCommand;
use Src\Application\Commands\CommandInterface;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardDescription;
use Src\Domain\Board\BoardName;
use Exception;
use Src\Infrastructure\Persistence\Repositories\Board\BoardRepositoryInterface;

final readonly class CreateBoardCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    /**
     * @throws Exception
     * @var CreateBoardCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $name = new BoardName($command->name);
        $existsByUserIdAndName = $this->boardRepository->existsByUserIdAndName($command->userId, $name);
        $board = new Board(
            existsByUserIdAndName: $existsByUserIdAndName,
            name: $name,
            userId: (int)$command->userId,
            description: $command->description ? new BoardDescription($command->description) : null,
        );
        $this->boardRepository->store($board);
    }
}
