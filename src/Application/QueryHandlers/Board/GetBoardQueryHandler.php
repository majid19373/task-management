<?php

namespace Src\Application\QueryHandlers\Board;

use Src\Application\Queries\Board\GetBoardQuery;
use Src\Domain\Board\Board;
use Exception;
use Src\Infrastructure\Persistence\Repositories\Board\BoardRepositoryInterface;

final readonly class GetBoardQueryHandler
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(GetBoardQuery $getBoardQuery): Board
    {
        return $this->boardRepository->getById($getBoardQuery->id);
    }
}
