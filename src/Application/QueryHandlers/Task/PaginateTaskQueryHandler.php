<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Infrastructure\Persistence\Repositories\PaginatedResult;
use Src\Domain\Task\{TaskPriority, TaskStatus};
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class PaginateTaskQueryHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    public function handle(PaginateTaskQuery $query): PaginatedResult
    {
        if($query->status){
            TaskStatus::validate($query->status);
        }
        if($query->priority){
            TaskPriority::validate($query->priority);
        }
        return $this->taskRepository->listWithPaginate($query);
    }
}
