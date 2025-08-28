<?php

namespace App\Services;

use App\DTO\Task\NewTask;
use App\DTO\Task\TaskFilter;
use App\Entities\Task;
use App\Repositories\Board\BoardRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use DateTimeImmutable;
use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskPriority, TaskStatus, TaskTitle};
use Exception;
use App\Repositories\PaginatedResult;

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
    public function list(TaskFilter $taskFilter): PaginatedResult|array
    {
        if($taskFilter->status){
            TaskStatus::validate($taskFilter->status);
        }
        if($taskFilter->priority){
            TaskPriority::validate($taskFilter->priority);
        }
        if($taskFilter->isPaginated){
            return $this->taskRepository->listWithPaginate($taskFilter);
        }else{
            return $this->taskRepository->list($taskFilter);
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
            description: $newTask->description ? new TaskDescription($newTask->description) : null,
            deadline: $newTask->deadline ? new TaskDeadline($newTask->deadline, new DateTimeImmutable()) : null,
        );
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function findById(int $taskId): Task
    {
        return $this->taskRepository->getById($taskId);
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->start();
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function complete(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->complete();
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->reopen();
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function prioritize(int $taskId, string $priority): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->prioritize(TaskPriority::validate($priority));
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function changeDeadline(int $taskId, string $deadline): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->changeDeadline(new TaskDeadline($deadline, new DateTimeImmutable()));
        $this->taskRepository->update($task);
    }
}
