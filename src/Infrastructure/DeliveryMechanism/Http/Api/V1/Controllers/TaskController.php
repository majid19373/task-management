<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\{DeadlineTaskRequest, ListTaskRequest};
use Src\Application\Bus\{CommandBus, PaginateQueryBus, QueryBus};
use Src\Application\CommandHandlers\Task\{
    AddTaskCommandHandler,
    ChangeDeadlineTaskCommandHandler,
    CompleteTaskCommandHandler,
    PrioritizeTaskCommandHandler,
    ReopenTaskCommandHandler,
    StartTaskCommandHandler
};
use Src\Application\Commands\Task\{CompleteTaskCommand, ReopenTaskCommand, StartTaskCommand};
use Src\Application\Queries\Task\{FindTaskQuery};
use Src\Application\QueryHandlers\Task\{FindTaskQueryHandler};
use Src\Application\QueryHandlers\Task\ListTaskQueryHandler;
use Src\Application\QueryHandlers\Task\PaginateTaskQueryHandler;
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
    public function list(ListTaskRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $bus = new QueryBus(resolve(ListTaskQueryHandler::class));
        $tasks = $bus->ask($query);

        return $this->respond(
            data: TaskResource::toArrayList($tasks),
        );
    }


    public function paginate(PaginateTaskRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $bus = new PaginateQueryBus(resolve(PaginateTaskQueryHandler::class));
        $tasks = $bus->ask($query);

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

        $bus = new CommandBus(resolve(AddTaskCommandHandler::class));
        $bus->dispatch($command);

        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function show(int $taskId): JsonResponse
    {
        $query = new FindTaskQuery($taskId);

        $bus = new QueryBus(resolve(FindTaskQueryHandler::class));
        $task = $bus->ask($query);

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

        $bus = new CommandBus(resolve(StartTaskCommandHandler::class));
        $bus->dispatch($command);

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

        $bus = new CommandBus(resolve(CompleteTaskCommandHandler::class));
        $bus->dispatch($command);

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

        $bus = new CommandBus(resolve(ReopenTaskCommandHandler::class));
        $bus->dispatch($command);

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

        $bus = new CommandBus(resolve(PrioritizeTaskCommandHandler::class));
        $bus->dispatch($command);

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

        $bus = new CommandBus(resolve(ChangeDeadlineTaskCommandHandler::class));
        $bus->dispatch($command);

        return $this->respondUpdated(
            message: 'The task was changed deadline.',
        );
    }

}
