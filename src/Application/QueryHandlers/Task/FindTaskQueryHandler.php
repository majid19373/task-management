<?php

namespace Src\Application\QueryHandlers\Task;

use Src\Application\Queries\QueryInterface;
use Src\Application\Queries\Task\FindTaskQuery;
use Exception;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Domain\Task\Task;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class FindTaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @return Task
     * @var FindTaskQuery $query
     */
    public function handle(QueryInterface $query): Task
    {
        return $this->taskRepository->getById($query->id);
    }
}
