<?php

namespace Src\Application\CommandHandlers\Task;

use Src\Application\Commands\Task\CompleteTaskCommand;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class CompleteTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    public function handle(CompleteTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->complete();
        $this->taskRepository->store($task);
    }
}
