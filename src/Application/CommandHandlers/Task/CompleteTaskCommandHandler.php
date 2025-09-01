<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\Commands\Task\CompleteTaskCommand;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class CompleteTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(CompleteTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->complete();
        $this->taskRepository->store($task);
    }
}
