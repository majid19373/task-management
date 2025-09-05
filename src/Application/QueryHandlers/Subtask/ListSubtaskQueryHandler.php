<?php

namespace Src\Application\QueryHandlers\Subtask;

use Src\Application\Queries\QueryInterface;
use Src\Application\Queries\Subtask\ListSubtaskQuery;
use Doctrine\Common\Collections\Collection;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class ListSubtaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    /**
     * @var ListSubtaskQuery $query
     */
    public function handle(QueryInterface $query): Collection
    {
        return $this->taskRepository->getById($query->taskId)->getSubtasks();
    }
}
