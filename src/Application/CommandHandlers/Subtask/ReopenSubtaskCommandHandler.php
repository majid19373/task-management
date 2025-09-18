<?php

namespace Src\Application\CommandHandlers\Subtask;

use Src\Application\Commands\Subtask\ReopenSubtaskCommand;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class ReopenSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    public function handle(ReopenSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->reopenSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
