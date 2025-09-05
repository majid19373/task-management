<?php

namespace Src\Application\QueryHandlers\Board;

use Src\Application\Queries\Board\GetBoardQuery;
use Src\Application\Queries\QueryInterface;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Domain\Board\Board;
use Exception;
use Src\Infrastructure\Persistence\Repositories\Board\BoardRepositoryInterface;

final readonly class GetBoardQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    /**
     * @throws Exception
     * @var GetBoardQuery $query
     */
    public function handle(QueryInterface $query): Board
    {
        return $this->boardRepository->getById($query->id);
    }
}
