<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\QueryInterface;
use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Infrastructure\Persistence\Repositories\PaginatedResult;
use Src\Domain\Task\{TaskPriority, TaskStatus};
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class PaginateTaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    /**
     * @var PaginateTaskQuery $query
     */
    public function handle(QueryInterface $query): PaginatedResult
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
