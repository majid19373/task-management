<?php

namespace Src\Application\CommandHandlers\Task;

use DateTimeImmutable;
use Exception;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;
use Src\Application\Commands\Task\ChangeDeadlineTaskCommand;
use Src\Domain\Task\TaskDeadline;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class ChangeDeadlineTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     * @var ChangeDeadlineTaskCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $task = $this->taskRepository->getById($command->id);
        $task->changeDeadline(new TaskDeadline($command->deadline, new DateTimeImmutable()));
        $this->taskRepository->store($task);
    }
}
