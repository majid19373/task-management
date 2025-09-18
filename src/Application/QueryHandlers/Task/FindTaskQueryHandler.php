<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\Task\FindTaskQuery;
use Src\Domain\Task\Task;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class FindTaskQueryHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    public function handle(FindTaskQuery $query): Task
    {
        return $this->taskRepository->getById($query->id);
    }
}
