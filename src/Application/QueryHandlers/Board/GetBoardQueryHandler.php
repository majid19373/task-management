<?php

namespace Src\Application\QueryHandlers\Board;

use Src\Application\Queries\Board\GetBoardQuery;
use Src\Domain\Board\Board;
use Src\Application\Contracts\Repositories\BoardRepositoryInterface;

final readonly class GetBoardQueryHandler
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    public function handle(GetBoardQuery $query): Board
    {
        return $this->boardRepository->getById($query->id);
    }
}
