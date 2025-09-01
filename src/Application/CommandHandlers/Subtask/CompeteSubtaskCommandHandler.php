<?php

namespace Src\Application\CommandHandlers\Subtask;

use Exception;
use Src\Application\Commands\Subtask\CompleteSubtaskCommand;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class CompeteSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(CompleteSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->completeSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
