<?php

namespace Src\Application\QueryHandlers\Board;

use Src\Application\Queries\Board\PaginateBoardQuery;
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Application\Repositories\PaginatedResult;

final readonly class PaginatedListBoardQueryHandler
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    public function handle(PaginateBoardQuery $query): PaginatedResult
    {
        return $this->boardRepository->getWithPaginate($query->page, $query->perPage);
    }
}
