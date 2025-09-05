<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\QueryInterface;
use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Domain\Task\{TaskPriority, TaskStatus};
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class ListTaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    /**
     *@var ListTaskQuery $query
     */
    public function handle(QueryInterface $query): array
    {
        if($query->status){
            TaskStatus::validate($query->status);
        }
        if($query->priority){
            TaskPriority::validate($query->priority);
        }
        return $this->taskRepository->list($query);
    }
}
