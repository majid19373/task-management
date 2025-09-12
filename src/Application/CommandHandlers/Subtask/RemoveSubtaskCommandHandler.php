<?php

namespace Src\Application\CommandHandlers\Subtask;

use Src\Application\Commands\Subtask\RemoveSubtaskCommand;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class RemoveSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    public function handle(RemoveSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->removeSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
