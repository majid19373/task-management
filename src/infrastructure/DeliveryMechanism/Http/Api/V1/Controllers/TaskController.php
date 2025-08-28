<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\{DeadlineTaskRequest};
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\AddTaskRequest;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\FilterTaskRequest;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\PriorityTaskRequest;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Task\TaskResource;
use Src\application\Services\TaskService;
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
    public function add(AddTaskRequest $request): JsonResponse
    {
        $taskDTO = $request->makeDTO();
        $this->taskService->add($taskDTO);
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
    public function complete(int $taskId): JsonResponse
    {
        $this->taskService->complete($taskId);
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
    public function prioritize(PriorityTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->taskService->prioritize(
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
