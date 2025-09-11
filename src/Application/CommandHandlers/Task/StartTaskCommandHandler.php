<?php

namespace Src\Application\CommandHandlers\Task;

use Src\Application\Commands\Task\StartTaskCommand;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class StartTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    public function handle(StartTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->start();
        $this->taskRepository->store($task);
    }
}
