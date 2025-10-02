<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class ListTaskQueryHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    public function handle(ListTaskQuery $query): array
    {
        return $this->taskRepository->list($query);
    }
}
