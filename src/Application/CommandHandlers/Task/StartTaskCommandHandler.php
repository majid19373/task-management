<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;
use Src\Application\Commands\Task\StartTaskCommand;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class StartTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     * @var StartTaskCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->start();
        $this->taskRepository->store($task);
    }
}
