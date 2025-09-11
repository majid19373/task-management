<?php

namespace Src\Application\CommandHandlers\Subtask;

use Src\Application\Commands\Subtask\AddSubtaskCommand;
use Src\Domain\Subtask\SubtaskDescription;
use Src\Domain\Subtask\SubtaskTitle;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class AddSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    public function handle(AddSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->addSubtask(
            subtaskNextId: $this->taskRepository->getNextSubtaskIdentity(),
            title: new SubtaskTitle($command->title),
            description: $command->description ? new SubtaskDescription($command->description) : null,
        );
        $this->taskRepository->store($task);
    }
}
