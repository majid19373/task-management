<?php

namespace Src\Application\CommandHandlers\Task;

use DateTimeImmutable;
use Src\Application\Commands\Task\ChangeDeadlineTaskCommand;
use Src\Domain\Task\TaskDeadline;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class ChangeDeadlineTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    public function handle(ChangeDeadlineTaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->changeDeadline(new TaskDeadline($command->deadline, new DateTimeImmutable()));
        $this->taskRepository->store($task);
    }
}
