<?php

namespace App\Services;

use App\DTO\ServicesResultDTO;
use App\DTO\Task\TaskDTO;
use App\DTO\Task\TaskFilterDTO;
use App\Entities\Task;
use App\Enums\TaskStatusEnum;
use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\Board\BoardRepository;
use App\Repositories\Task\TaskRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response as Res;

final class TaskService extends BaseService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly BoardRepository $boardRepository,
    )
    {}

    public function index(TaskFilterDTO $taskFilterDTO): ServicesResultDTO
    {
        if($taskFilterDTO->is_paginated){
            $result = $this->taskRepository->getWithPaginate($taskFilterDTO->per_page, TaskResource::JSON_STRUCTURE);
        }else{
            $result = $this->taskRepository->all(TaskResource::JSON_STRUCTURE);
        }
        return $this->successResult(
            data: $result,
        );
    }

    /**
     * @throws Exception
     */
    public function store(TaskDTO $taskDTO): ServicesResultDTO
    {
        $this->checkDeadlineValid($taskDTO->deadline);
        $this->boardRepository->findOrFailedById($taskDTO->board_id, BoardResource::JSON_STRUCTURE);
        $task = $this->makeEntity($taskDTO);
        $taskId = $this->taskRepository->store($task);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function findById(int $taskId): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus() !== TaskStatusEnum::NOT_STARTED->value){
            $this->throwException(
                message: 'The task must not have started.',
            );
        }
        $task->setStatus(TaskStatusEnum::IN_PROGRESS->value);
        $this->taskRepository->update($task);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function completed(int $taskId): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus() !== TaskStatusEnum::IN_PROGRESS->value){
            $this->throwException(
                message: 'The task must not have completed.',
            );
        }
        $task->setStatus(TaskStatusEnum::COMPLETED->value);
        $this->taskRepository->update($task);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus() !== TaskStatusEnum::COMPLETED->value){
            $this->throwException(
                message: 'The task cannot reopened.',
            );
        }
        $task->setStatus(TaskStatusEnum::NOT_STARTED->value);
        $this->taskRepository->update($task);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function priority(int $taskId, string $priority): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus() === TaskStatusEnum::COMPLETED->value){
            $this->throwException(
                message: 'The task cannot change the priority.',
            );
        }
        $task->setPriority($priority);
        $this->taskRepository->update($task);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function deadline(int $taskId, Carbon $deadline): ServicesResultDTO
    {
        $this->checkDeadlineValid($deadline);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus() === TaskStatusEnum::COMPLETED->value){
            $this->throwException(
                message: 'The task cannot change the deadline.',
            );
        }
        $task->setDeadline($deadline);
        $this->taskRepository->update($task);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    private function checkDeadlineValid(?Carbon $deadline): void
    {
        if($deadline && $deadline->diffInMinutes(Carbon::now()) > 1){
            $this->throwException(
                message: 'The deadline field must be a valid date',
            );
        }
    }

    private function makeEntity(TaskDTO $task): Task
    {
        return new Task(
            id: (int)$task->id,
            title: $task->title,
            boardId: (int)$task->board_id,
            description: $task->description,
            status: $task->status,
            priority: $task->priority,
            deadline: $task->deadline ? Carbon::make($task->deadline) : null,
        );
    }
}
