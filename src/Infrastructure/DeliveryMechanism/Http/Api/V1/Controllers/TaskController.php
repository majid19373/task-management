<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\{DeadlineTaskRequest, ListTaskRequest};
use Src\Application\Bus\{CommandBus, QueryBus};
use Src\Application\Commands\Task\{CompleteTaskCommand, ReopenTaskCommand, StartTaskCommand};
use Src\Application\Queries\Task\{FindTaskQuery};
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common\Controller;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\{
    AddTaskRequest,
    PaginateTaskRequest,
    PriorityTaskRequest
};
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Task\TaskResource;
use Illuminate\Http\JsonResponse;
use Exception;

final class TaskController extends Controller
{
    public function __construct(
        private readonly QueryBus   $queryBus,
        private readonly CommandBus $commandBus,
    )
    {}

    public function list(ListTaskRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $tasks = $this->queryBus->ask($query);

        return $this->respond(
            data: TaskResource::toArrayList($tasks),
        );
    }


    public function paginate(PaginateTaskRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $tasks = $this->queryBus->ask($query);

        return $this->respondWithPagination(
            paginate: $tasks->paginator,
            data: TaskResource::toArrayList($tasks->list),
        );
    }

    /**
     * @throws Exception
     */
    public function add(AddTaskRequest $request): JsonResponse
    {
        $command = $request->makeDTO();

        $this->commandBus->dispatch($command);

        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function show(int $taskId): JsonResponse
    {
        $query = new FindTaskQuery($taskId);

        $task = $this->queryBus->ask($query);

        return $this->respond(
            data: TaskResource::toArray($task),
        );
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): JsonResponse
    {
        $command = new StartTaskCommand($taskId);

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The task was started.',
        );
    }

    /**
     * @throws Exception
     */
    public function complete(int $taskId): JsonResponse
    {
        $command = new CompleteTaskCommand($taskId);

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The task was completed.',
        );
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): JsonResponse
    {
        $command = new ReopenTaskCommand($taskId);

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The task was reopened.',
        );
    }

    /**
     * @throws Exception
     */
    public function prioritize(PriorityTaskRequest $request): JsonResponse
    {
        $command = $request->makeDTO();

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The task was changed priority.',
        );
    }

    /**
     * @throws Exception
     */
    public function changeDeadline(DeadlineTaskRequest $request): JsonResponse
    {
        $command = $request->makeDTO();

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The task was changed deadline.',
        );
    }

}
