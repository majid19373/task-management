<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\Task\TaskEditResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\{DeadlineTaskRequest, FilterTaskRequest, CreateTaskRequest, PriorityTaskRequest};
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

    /**
     * @throws Exception
     */
    public function list(FilterTaskRequest $request): JsonResponse
    {
        $taskFilterDTO = $request->makeDTO();
        $tasks = $this->taskService->list($taskFilterDTO);
        if($taskFilterDTO->isPaginated){
            return $this->respondWithPagination(
                paginate: $tasks->paginator,
                data: TaskResource::toArrayList($tasks->list),
            );
        }
        return $this->respond(
            data: TaskResource::toArrayList($tasks),
        );
    }

    /**
     * @throws Exception
     */
    public function create(CreateTaskRequest $request): JsonResponse
    {
        $taskDTO = $request->makeDTO();
        $this->taskService->create($taskDTO);
        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function show(int $taskId): JsonResponse
    {
        $task = $this->taskService->findById($taskId);
        return $this->respond(
            data: TaskResource::toArray($task),
        );
    }

    /**
     * @throws Exception
     */
    public function showWithStatusPriorityFields(int $taskId): JsonResponse
    {
        $task = $this->taskService->findById($taskId);
        return $this->respond(
            data: TaskEditResource::toArray($task),
        );
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): JsonResponse
    {
        $this->taskService->start($taskId);
        return $this->respondUpdated(
            message: 'The task was started.',
        );
    }

    /**
     * @throws Exception
     */
    public function completed(int $taskId): JsonResponse
    {
        $this->taskService->completed($taskId);
        return $this->respondUpdated(
            message: 'The task was completed.',
        );
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): JsonResponse
    {
        $this->taskService->reopen($taskId);
        return $this->respondUpdated(
            message: 'The task was reopened.',
        );
    }

    /**
     * @throws Exception
     */
    public function changePriority(PriorityTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->taskService->changePriority(
            taskId: $validated['id'],
            priority: $validated['priority'],
        );
        return $this->respondUpdated(
            message: 'The task was changed priority.',
        );
    }

    /**
     * @throws Exception
     */
    public function changeDeadline(DeadlineTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->taskService->changeDeadline(
            taskId: $validated['id'],
            deadline: $validated['deadline'],
        );
        return $this->respondUpdated(
            message: 'The task was changed deadline.',
        );
    }

}
