<?php

namespace Src\Application\CommandHandlers\Subtask;

use Src\Application\Commands\Subtask\AddSubtaskCommand;
use Exception;
use Src\Domain\Subtask\SubtaskDescription;
use Src\Domain\Subtask\SubtaskTitle;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepositoryInterface;

final readonly class AddSubtaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function handle(AddSubtaskCommand $command): void
    {
        $task = $this->taskRepository->getById($command->taskId);
        $task->addSubtask(
            title: new SubtaskTitle($command->title),
            description: $command->description ? new SubtaskDescription($command->description) : null,
        );
        $this->taskRepository->store($task);
    }
}
