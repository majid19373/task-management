<?php

namespace Src\application\Services;

use Src\application\DTO\Subtask\NewSubtask;
use Src\application\DTO\Subtask\SubtaskFilter;
use Src\domain\Entities\Subtask\ValueObjects\SubtaskDescription;
use Src\persistence\Repositories\Task\TaskRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Src\domain\Entities\Subtask\ValueObjects\{SubtaskTitle};
use Exception;

final readonly class SubtaskService
{
    public function __construct(
        private TaskRepositoryInterface    $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(SubtaskFilter $subtaskFilter): Collection
    {
        return $this->taskRepository->getById($subtaskFilter->taskId)->getSubtasks();
    }

    /**
     * @throws Exception
     */
    public function add(NewSubtask $newSubTask): void
    {
        $task = $this->taskRepository->getById($newSubTask->taskId);
        $task->addSubtask(
            title: new SubtaskTitle($newSubTask->title),
            description: $newSubTask->description ? new SubtaskDescription($newSubTask->description) : null,
        );
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId, int $subtaskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->startSubtask($subtaskId);

        $this->taskRepository->store($task);
    }

    public function complete(int $subtaskId): void
    {
        $task = $this->taskRepository->getBySubtaskId($subtaskId);
        $task->completeSubtask($subtaskId);

        $this->taskRepository->store($task);
    }

    public function reopen(int $subtaskId): void
    {
        $task = $this->taskRepository->getBySubtaskId($subtaskId);
        $task->reopenSubtask($subtaskId);

        $this->taskRepository->store($task);
    }
}
