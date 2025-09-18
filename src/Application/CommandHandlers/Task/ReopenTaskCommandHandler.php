<?php

namespace Src\Application\CommandHandlers\Task;

use Src\Application\Commands\Task\ReopenTaskCommand;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class ReopenTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    public function handle(ReopenTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->reopen();
        $this->taskRepository->store($task);
    }
}
