<?php

namespace Src\Application\QueryHandlers\Subtask;

use Src\Application\Queries\Subtask\ListSubtaskQuery;
use Doctrine\Common\Collections\Collection;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class ListSubtaskQueryHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    public function handle(ListSubtaskQuery $query): Collection
    {
        return $this->taskRepository->getById($query->taskId)->getSubtasks();
    }
}
