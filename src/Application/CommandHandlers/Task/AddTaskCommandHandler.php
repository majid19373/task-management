<?php

namespace Src\Application\CommandHandlers\Task;

use DateTimeImmutable;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;
use Src\Application\Commands\Task\AddTaskCommand;
use Exception;
use Src\Domain\Task\{TaskDeadline, TaskDescription, TaskTitle};
use Src\Application\Contracts\Repositories\BoardRepositoryInterface;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class AddTaskCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
        private BoardRepositoryInterface $boardRepository,
    )
    {}

    /**
     * @throws Exception
     * @var AddTaskCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $board = $this->boardRepository->getById($command->boardId);
        $task = $board->addTask(
            title: new TaskTitle($command->title),
            description: $command->description ? new TaskDescription($command->description) : null,
            deadline: $command->deadline ? new TaskDeadline($command->deadline, new DateTimeImmutable()) : null,
        );
        $this->taskRepository->store($task);
    }
}
