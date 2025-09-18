<?php

namespace Src\Application\CommandHandlers\Task;

use Src\Application\Commands\Task\PrioritizeTaskCommand;
use Src\Domain\Task\TaskPriority;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class PrioritizeTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    public function handle(PrioritizeTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->prioritize(TaskPriority::validate($command->priority));
        $this->taskRepository->store($task);
    }
}
