<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\Commands\Task\ReopenTaskCommand;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class ReopenTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(ReopenTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->reopen();
        $this->taskRepository->store($task);
    }
}
