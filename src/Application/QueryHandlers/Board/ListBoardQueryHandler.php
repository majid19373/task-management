<?php

namespace Src\Application\QueryHandlers\Board;

use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Application\Queries\Board\ListBoardQuery;

final readonly class ListBoardQueryHandler
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    public function handle(ListBoardQuery $query): array
    {
        return $this->boardRepository->getAll();
    }
}
