<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\Commands\Task\PrioritizeTaskCommand;
use Src\Domain\Task\TaskPriority;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class PrioritizeTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(PrioritizeTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->prioritize(TaskPriority::validate($command->priority));
        $this->taskRepository->store($task);
    }
}
