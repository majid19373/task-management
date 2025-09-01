<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\Commands\Task\StartTaskCommand;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class StartTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(StartTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->start();
        $this->taskRepository->store($task);
    }
}
