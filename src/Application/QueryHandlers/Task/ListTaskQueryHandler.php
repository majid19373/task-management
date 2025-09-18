<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\Task\ListTaskQuery;
use Src\Domain\Task\{TaskPriority, TaskStatus};
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class ListTaskQueryHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    public function handle(ListTaskQuery $query): array
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
