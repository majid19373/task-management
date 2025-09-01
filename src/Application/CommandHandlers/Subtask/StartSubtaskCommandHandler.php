<?php

namespace Src\Application\CommandHandlers\Subtask;

use Exception;
use Src\Application\Commands\Subtask\StartSubtaskCommand;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class StartSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(StartSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->startSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
