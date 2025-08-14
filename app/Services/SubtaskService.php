<?php

namespace App\Services;

use App\DTO\Subtask\NewSubtask;
use App\DTO\Subtask\SubtaskFilter;
use App\Repositories\PaginatedResult;
use App\Repositories\Subtask\SubtaskRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\ValueObjects\Task\TaskStatus;
use Illuminate\Support\Collection;
use App\ValueObjects\Subtask\{SubtaskDescription, SubtaskTitle};
use Exception;

final readonly class SubtaskService
{
    public function __construct(
        private TaskRepositoryInterface    $taskRepository,
        private SubtaskRepositoryInterface $subtaskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(SubtaskFilter $subtaskFilter): PaginatedResult|Collection
    {
        if($subtaskFilter->isPaginated){
            return $this->subtaskRepository->listWithPaginate($subtaskFilter);
        }else{
            return $this->subtaskRepository->list($subtaskFilter);
        }
    }

    /**
     * @throws Exception
     */
    public function add(NewSubtask $newSubTask): void
    {
        $task = $this->taskRepository->getById($newSubTask->taskId);
        $isCompletedTask = $task->getStatus() === TaskStatus::COMPLETED;
        $subTask = $task->addSubtask(
            title: SubtaskTitle::createNew($newSubTask->title),
            isCompletedTask: $isCompletedTask,
            description: $newSubTask->description ? SubtaskDescription::reconstitute($newSubTask->description) : null,
        );
        $this->subtaskRepository->store($subTask);
    }

    /**
     * @throws Exception
     */
    public function start(int $subtaskId): void
    {
        $subtask = $this->subtaskRepository->getById($subtaskId);
        $task = $this->taskRepository->getById($subtask->getTaskId());

        $subtask->start($task->getStatus());
        $this->subtaskRepository->update($subtask);

        $task->start();
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function completed(int $taskId): void
    {
        $subtask = $this->subtaskRepository->getById($taskId);
        $subtask->completed();
        $this->subtaskRepository->update($subtask);
    }
}
