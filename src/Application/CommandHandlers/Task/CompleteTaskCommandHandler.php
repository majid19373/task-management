<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;
use Src\Application\Commands\Task\CompleteTaskCommand;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class CompleteTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     * @var CompleteTaskCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->complete();
        $this->taskRepository->store($task);
    }
}
