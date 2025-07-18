<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\Task\TaskEditResource;
use App\DTO\Task\{TaskDTO, TaskFilterDTO};
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\{DeadlineTaskRequest, FilterTaskRequest, StoreTaskRequest, PriorityTaskRequest};
use App\Http\Resources\Task\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Exception;

final class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
    )
    {}

    public function index(FilterTaskRequest $request): JsonResponse
    {
        $taskFilterDTO = TaskFilterDTO::make($request->validated());
        $boards = $this->taskService->index($taskFilterDTO);
        if($taskFilterDTO->is_paginated){
            return $this->respondWithPagination(
                paginate: $boards->data->paginator,
                data: TaskResource::toArrayList($boards->data->list),
            );
        }
        return $this->respond(
            data: TaskResource::toArrayList($boards->data),
        );
    }

    /**
     * @throws Exception
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $taskDTO = TaskDTO::make($request->validated());
        $result = $this->taskService->store($taskDTO);
        return $this->respondCreated(
            data: TaskResource::toArray($result->data),
        );
    }

    /**
     * @throws Exception
     */
    public function show(int $taskId): JsonResponse
    {
        $task = $this->taskService->findById($taskId);
        return $this->respond(
            data: TaskResource::toArray($task->data),
        );
    }

    /**
     * @throws Exception
     */
    public function edit(int $taskId): JsonResponse
    {
        $task = $this->taskService->findById($taskId);
        return $this->respond(
            data: TaskEditResource::toArray($task->data),
        );
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): JsonResponse
    {
        $task = $this->taskService->start($taskId);
        return $this->respondUpdated(
            data: TaskResource::toArray($task->data),
            message: 'The task was started.',
        );
    }

    /**
     * @throws Exception
     */
    public function completed(int $taskId): JsonResponse
    {
        $task = $this->taskService->completed($taskId);
        return $this->respondUpdated(
            data: TaskResource::toArray($task->data),
            message: 'The task was completed.',
        );
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): JsonResponse
    {
        $task = $this->taskService->reopen($taskId);
        return $this->respondUpdated(
            data: TaskResource::toArray($task->data),
            message: 'The task was reopened.',
        );
    }

    /**
     * @throws Exception
     */
    public function priority(PriorityTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $task = $this->taskService->priority(
            taskId: $validated['id'],
            priority: $validated['priority'],
        );
        return $this->respondUpdated(
            data: TaskResource::toArray($task->data),
            message: 'The task was changed priority.',
        );
    }

    /**
     * @throws Exception
     */
    public function deadline(DeadlineTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $task = $this->taskService->deadline(
            taskId: $validated['id'],
            deadline: $validated['deadline'],
        );
        return $this->respondUpdated(
            data: TaskResource::toArray($task->data),
            message: 'The task was changed deadline.',
        );
    }

}
