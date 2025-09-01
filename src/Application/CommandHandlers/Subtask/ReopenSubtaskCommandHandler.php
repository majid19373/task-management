<?php

namespace Src\Application\CommandHandlers\Subtask;

use Exception;
use Src\Application\Commands\Subtask\CompleteSubtaskCommand;
use Src\Application\Commands\Subtask\ReopenSubtaskCommand;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class ReopenSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(ReopenSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->reopenSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
