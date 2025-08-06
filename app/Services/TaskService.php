<?php

namespace App\Services;

use App\DTO\Task\NewTaskDTO;
use App\DTO\Task\TaskFilterDTO;
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
    public function list(TaskFilterDTO $taskFilterDTO): PaginatedResult|Collection
    {
        TaskStatus::validate($taskFilterDTO->status);
        TaskPriority::validate($taskFilterDTO->priority);
        if($taskFilterDTO->isPaginated){
            return $this->taskRepository->listWithPaginate($taskFilterDTO, TaskResource::JSON_STRUCTURE);
        }else{
            return $this->taskRepository->list($taskFilterDTO, TaskResource::JSON_STRUCTURE);
        }
    }

    /**
     * @throws Exception
     */
    public function add(NewTaskDTO $newTaskDTO): void
    {
        $board = $this->boardRepository->getById($newTaskDTO->boardId);
        $task = $board->addTask(
            title: new TaskTitle($newTaskDTO->title),
            description: $newTaskDTO->description ? new taskDescription($newTaskDTO->description) : null,
            deadline: $newTaskDTO->deadline ? new taskDeadline($newTaskDTO->deadline) : null,
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
        $task = $this->taskRepository->getById($taskId, TaskResource::JSON_STRUCTURE);
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
