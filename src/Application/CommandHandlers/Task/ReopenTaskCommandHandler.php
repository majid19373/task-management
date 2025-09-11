<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;
use Src\Application\Commands\Task\ReopenTaskCommand;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class ReopenTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     * @var ReopenTaskCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->reopen();
        $this->taskRepository->store($task);
    }
}
