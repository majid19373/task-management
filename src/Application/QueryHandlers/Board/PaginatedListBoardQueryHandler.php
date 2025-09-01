<?php

namespace Src\Application\QueryHandlers\Board;

use Src\Application\Queries\Board\PaginateBoardQuery;
use Exception;
use Src\Infrastructure\Persistence\Repositories\Board\BoardRepositoryInterface;
use Src\Infrastructure\Persistence\Repositories\PaginatedResult;

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
