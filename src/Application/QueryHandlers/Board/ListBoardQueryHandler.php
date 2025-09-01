<?php

namespace Src\Application\QueryHandlers\Board;

use Exception;
use Src\Infrastructure\Persistence\Repositories\Board\BoardRepositoryInterface;

final readonly class ListBoardQueryHandler
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    public function handle(): array
    {
        return $this->boardRepository->getAll();
    }
}
