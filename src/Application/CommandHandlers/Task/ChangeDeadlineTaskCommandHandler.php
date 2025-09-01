<?php

namespace Src\Application\CommandHandlers\Task;

use DateTimeImmutable;
use Exception;
use Src\Application\Commands\Task\ChangeDeadlineTaskCommand;
use Src\Domain\Task\TaskDeadline;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class ChangeDeadlineTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(ChangeDeadlineTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->changeDeadline(new TaskDeadline($command->deadline, new DateTimeImmutable()));
        $this->taskRepository->store($task);
    }
}
