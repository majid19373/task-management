<?php

namespace App\Services;

use App\DTO\Task\NewTask;
use App\DTO\Task\TaskFilter;
use App\Entities\Task;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\Board\BoardRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskPriority, TaskStatus, TaskTitle};
use Exception;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

final readonly class TaskService
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
        private BoardRepositoryInterface $boardRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(TaskFilter $taskFilter): PaginatedResult|Collection
    {
        TaskStatus::validate($taskFilter->status);
        TaskPriority::validate($taskFilter->priority);
        if($taskFilter->isPaginated){
            return $this->taskRepository->listWithPaginate($taskFilter, TaskResource::JSON_STRUCTURE);
        }else{
            return $this->taskRepository->list($taskFilter, TaskResource::JSON_STRUCTURE);
        }
    }

    /**
     * @throws Exception
     */
    public function add(NewTask $newTask): void
    {
        $board = $this->boardRepository->getById($newTask->boardId);
        $task = $board->addTask(
            title: new TaskTitle($newTask->title),
            description: $newTask->description ? new taskDescription($newTask->description) : null,
            deadline: $newTask->deadline ? new taskDeadline($newTask->deadline) : null,
        );
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function findById(int $taskId): Task
    {
        return $this->taskRepository->getById($taskId, TaskResource::JSON_STRUCTURE);
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId, TaskResource::JSON_STRUCTURE);
        $task->start();
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function completed(int $taskId): void
    {
        $task = $this->taskRepository->getByIdIfSubtasksAreCompleted($taskId);
        $task->completed();
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId, TaskResource::JSON_STRUCTURE);
        $task->reopen();
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function changePriority(int $taskId, string $priority): void
    {
        $task = $this->taskRepository->getById($taskId, TaskResource::JSON_STRUCTURE);
        $task->setPriority(TaskPriority::toCase($priority));
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function changeDeadline(int $taskId, string $deadline): void
    {
        $task = $this->taskRepository->getById($taskId, TaskResource::JSON_STRUCTURE);
        $task->setDeadline(new TaskDeadline($deadline));
        $this->taskRepository->update($task);
    }
}
