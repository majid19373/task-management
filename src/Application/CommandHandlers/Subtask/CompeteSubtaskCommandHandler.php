<?php

namespace Src\Application\CommandHandlers\Subtask;

use Src\Application\Commands\Subtask\CompleteSubtaskCommand;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class CompeteSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    public function handle(CompleteSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->completeSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
