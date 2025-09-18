<?php

namespace Src\Application\CommandHandlers\Subtask;

use Src\Application\Commands\Subtask\StartSubtaskCommand;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class StartSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    public function handle(StartSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->startSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
