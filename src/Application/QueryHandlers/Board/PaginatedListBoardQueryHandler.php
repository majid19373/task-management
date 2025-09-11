<?php

namespace Src\Application\QueryHandlers\Board;

use Src\Application\Queries\Board\PaginateBoardQuery;
use Src\Application\Queries\QueryInterface;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Application\Contracts\Repositories\BoardRepositoryInterface;
use Src\Application\Contracts\Repositories\PaginatedResult;

final readonly class PaginatedListBoardQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    /**
     * @var PaginateBoardQuery $query
     */
    public function handle(QueryInterface $query): PaginatedResult
    {
        return $this->boardRepository->getWithPaginate($query->page, $query->perPage);
    }
}
