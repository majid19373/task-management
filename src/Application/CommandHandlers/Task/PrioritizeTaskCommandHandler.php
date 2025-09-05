<?php

namespace Src\Application\CommandHandlers\Task;

use Exception;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;
use Src\Application\Commands\Task\PrioritizeTaskCommand;
use Src\Domain\Task\TaskPriority;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class PrioritizeTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     * @var PrioritizeTaskCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->prioritize(TaskPriority::validate($command->priority));
        $this->taskRepository->store($task);
    }
}
