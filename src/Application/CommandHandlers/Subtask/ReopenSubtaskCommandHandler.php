<?php

namespace Src\Application\CommandHandlers\Subtask;

use Exception;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;
use Src\Application\Commands\Subtask\ReopenSubtaskCommand;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class ReopenSubtaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    /**
     * @throws Exception
     * @var ReopenSubtaskCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->reopenSubtask($command->subtaskId);

        $this->taskRepository->store($task);
    }
}
