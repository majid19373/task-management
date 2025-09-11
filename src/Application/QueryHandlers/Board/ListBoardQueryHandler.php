<?php

namespace Src\Application\QueryHandlers\Board;

use Exception;
use Src\Application\Queries\QueryInterface;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Application\Contracts\Repositories\BoardRepositoryInterface;

final readonly class ListBoardQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    public function handle(QueryInterface $query): array
    {
        return $this->boardRepository->getAll();
    }
}
