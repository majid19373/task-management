<?php

namespace App\Services;

use App\DTO\ServicesResultDTO;
use App\DTO\Task\TaskDTO;
use App\DTO\Task\TaskFilterDTO;
use App\Enums\TaskStatusEnum;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\TaskRepository;
use Illuminate\Http\Response as Res;
use Exception;

final class TaskService extends BaseService
{
    public function __construct(
        private readonly TaskRepository $taskRepository
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
        $result = $this->taskRepository->store($taskDTO->toArray());
        $this->throwExceptionIfNotStore($result);
        $task = $this->taskRepository->findOrFailedById($result->id, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function findById(int $boardId): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($boardId, TaskResource::JSON_STRUCTURE);
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
        if($task->status !== TaskStatusEnum::NOT_STARTED->value){
            $this->throwException(
                message: 'The task must not have started.',
                statusCode: Res::HTTP_CONFLICT,
            );
        }
        $data = [
            'status' => TaskStatusEnum::IN_PROGRESS->value
        ];
        $result = $this->taskRepository->updateWithModel($task, $data);
        $this->throwExceptionIfNotUpdate($result);
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
        if($task->status !== TaskStatusEnum::IN_PROGRESS->value){
            $this->throwException(
                message: 'The task must not have completed.',
                statusCode: Res::HTTP_CONFLICT,
            );
        }
        $data = [
            'status' => TaskStatusEnum::COMPLETED->value
        ];
        $result = $this->taskRepository->updateWithModel($task, $data);
        $this->throwExceptionIfNotUpdate($result);
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
        if($task->status !== TaskStatusEnum::COMPLETED->value){
            $this->throwException(
                message: 'The task cannot reopened.',
                statusCode: Res::HTTP_CONFLICT,
            );
        }
        $data = [
            'status' => TaskStatusEnum::NOT_STARTED->value
        ];
        $result = $this->taskRepository->updateWithModel($task, $data);
        $this->throwExceptionIfNotUpdate($result);
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
        if($task->status === TaskStatusEnum::COMPLETED->value){
            $this->throwException(
                message: 'The task cannot change the priority.',
                statusCode: Res::HTTP_CONFLICT,
            );
        }
        $data = [
            'priority' => $priority
        ];
        $result = $this->taskRepository->updateWithModel($task, $data);
        $this->throwExceptionIfNotUpdate($result);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }

    /**
     * @throws Exception
     */
    public function deadline(int $taskId, string $deadline): ServicesResultDTO
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->status === TaskStatusEnum::COMPLETED->value){
            $this->throwException(
                message: 'The task cannot change the deadline.',
                statusCode: Res::HTTP_CONFLICT,
            );
        }
        $data = [
            'deadline' => $deadline
        ];
        $result = $this->taskRepository->updateWithModel($task, $data);
        $this->throwExceptionIfNotUpdate($result);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $task,
        );
    }
}
