<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Application\Repositories\PaginatedResult;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class PaginateTaskQueryHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    public function handle(PaginateTaskQuery $query): PaginatedResult
    {
        return $this->taskRepository->listWithPaginate($query);
    }
}
