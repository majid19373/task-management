<?php

namespace Src\Application\CommandHandlers\Task;

use Src\Application\Commands\Task\AddTaskCommand;
use Src\Domain\Task\{TaskDeadline, TaskDescription, TaskTitle};
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class AddTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
        private BoardRepositoryInterface $boardRepository,
    )
    {}

    public function handle(AddTaskCommand $command): void
    {
        $board = $this->boardRepository->getById($command->boardId);
        $task = $board->addTask(
            taskId: $this->taskRepository->getNextIdentity(),
            title: new TaskTitle($command->title),
            description: $command->description ? new TaskDescription($command->description) : null,
            deadline: $command->deadline ? new TaskDeadline($command->deadline) : null,
        );
        $this->taskRepository->store($task);
    }
}
