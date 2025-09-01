<?php

namespace Src\Application\CommandHandlers\Task;

use DateTimeImmutable;
use Src\Application\Commands\Task\AddTaskCommand;
use Exception;
use Src\Domain\Task\{TaskDeadline, TaskDescription, TaskTitle};
use Src\Infrastructure\Persistence\Repositories\Board\BoardRepositoryInterface;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class AddTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
        private BoardRepositoryInterface $boardRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(AddTaskCommand $command): void
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
