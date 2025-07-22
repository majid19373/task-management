<?php

namespace App\Services;

use App\DTO\ServicesResultDTO;
use App\DTO\Task\NewTaskDTO;
use App\DTO\Task\TaskFilterDTO;
use App\Entities\Task;
use App\Enums\TaskStatusEnum;
use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\Board\BoardRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Rules\Task\CheckPriority;
use App\Rules\Task\CheckStatus;
use App\ValueObjects\Task\TaskDeadline;
use App\ValueObjects\Task\TaskDescription;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskTitle;
use Carbon\Carbon;
use Exception;

final class TaskService extends BaseService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly BoardRepositoryInterface $boardRepository,
        private readonly CheckStatus $checkStatus,
        private readonly CheckPriority $checkPriority,
    )
    {}

    /**
     * @throws Exception
     */
    public function index(TaskFilterDTO $taskFilterDTO): ServicesResultDTO
    {
        $this->checkStatus->validate($taskFilterDTO->status);
        $this->checkPriority->validate($taskFilterDTO->priority);
        if($taskFilterDTO->is_paginated){
            $result = $this->taskRepository->getWithPaginate($taskFilterDTO, TaskResource::JSON_STRUCTURE);
        }else{
            $result = $this->taskRepository->all($taskFilterDTO, TaskResource::JSON_STRUCTURE);
        }
        return $this->successResult(
            data: $result,
        );
    }

    /**
     * @throws Exception
     */
    public function store(NewTaskDTO $taskDTO): ServicesResultDTO
    {
        if(!$this->boardRepository->isExist($taskDTO->board_id)){
            $this->throwException(
                message: 'The board does not exist.',
            );
        }
        if($taskDTO->parent_id && !$this->taskRepository->isExist($taskDTO->parent_id)){
            $this->throwException(
                message: 'The task does not exist.',
            );
        }
        $task = $this->makeEntity($taskDTO);
        $this->taskRepository->create($task);
        $task = $this->taskRepository->findOrFailedById($task->getId(), TaskResource::JSON_STRUCTURE);
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
        $task->setStatus(new TaskStatus(TaskStatusEnum::IN_PROGRESS->value));
        $this->taskRepository->update($task);
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
        $task->setStatus(new TaskStatus(TaskStatusEnum::COMPLETED->value));
        $this->taskRepository->update($task);
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
        $task->setStatus(new TaskStatus(TaskStatusEnum::NOT_STARTED->value));
        $this->taskRepository->update($task);
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
        $task->setPriority(new TaskPriority($priority));
        $this->taskRepository->update($task);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function deadline(int $taskId, Carbon $deadline): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus() === TaskStatusEnum::COMPLETED->value){
            $this->throwException(
                message: 'The task cannot change the deadline.',
            );
        }
        $task->setDeadline(new TaskDeadline($deadline));
        $this->taskRepository->update($task);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    private function makeEntity(NewTaskDTO $newTaskDTO): Task
    {
        return new Task(
            boardId: (int)$newTaskDTO->board_id,
            title: new TaskTitle($newTaskDTO->title),
            parentId: $newTaskDTO->parent_id,
            description: new TaskDescription($newTaskDTO->description),
            deadline: new TaskDeadline($newTaskDTO->deadline),
        );
    }
}
