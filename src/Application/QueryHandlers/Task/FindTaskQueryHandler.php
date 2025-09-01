<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\Task\FindTaskQuery;
use Exception;
use Src\Domain\Task\Task;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class FindTaskQueryHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(FindTaskQuery $query): Task
    {
        return $this->taskRepository->getById($query->id);
    }
}
