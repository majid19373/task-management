<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\{DeadlineTaskRequest, ListTaskRequest};
use Src\Application\CommandHandlers\Task\AddTaskCommandHandler;
use Src\Application\CommandHandlers\Task\ChangeDeadlineTaskCommandHandler;
use Src\Application\CommandHandlers\Task\CompleteTaskCommandHandler;
use Src\Application\CommandHandlers\Task\PrioritizeTaskCommandHandler;
use Src\Application\CommandHandlers\Task\ReopenTaskCommandHandler;
use Src\Application\CommandHandlers\Task\StartTaskCommandHandler;
use Src\Application\Commands\Task\CompleteTaskCommand;
use Src\Application\Commands\Task\ReopenTaskCommand;
use Src\Application\Commands\Task\StartTaskCommand;
use Src\Application\Queries\Task\FindTaskQuery;
use Src\Application\QueryHandlers\Task\FindTaskQueryHandler;
use Src\Application\QueryHandlers\Task\ListTaskQueryHandler;
use Src\Application\QueryHandlers\Task\PaginateTaskQueryHandler;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common\Controller;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\AddTaskRequest;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\PaginateTaskRequest;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task\PriorityTaskRequest;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Task\TaskResource;
use Illuminate\Http\JsonResponse;
use Exception;

final class TaskController extends Controller
{
    public function __construct(
        private readonly ListTaskQueryHandler             $listTaskQueryHandler,
        private readonly PaginateTaskQueryHandler         $paginateTaskQueryHandler,
        private readonly AddTaskCommandHandler            $addTaskCommandHandler,
        private readonly FindTaskQueryHandler             $findTaskQueryHandler,
        private readonly StartTaskCommandHandler          $startTaskCommandHandler,
        private readonly CompleteTaskCommandHandler       $completeTaskCommandHandler,
        private readonly ReopenTaskCommandHandler         $reopenTaskCommandHandler,
        private readonly PrioritizeTaskCommandHandler     $prioritizeTaskCommandHandler,
        private readonly ChangeDeadlineTaskCommandHandler $changeDeadlineTaskCommandHandler,
    )
    {}

    public function list(ListTaskRequest $request): JsonResponse
    {
        $query = $request->makeDTO();
        $tasks = $this->listTaskQueryHandler->handle($query);
        return $this->respond(
            data: TaskResource::toArrayList($tasks),
        );
    }


    public function paginate(PaginateTaskRequest $request): JsonResponse
    {
        $query = $request->makeDTO();
        $tasks = $this->paginateTaskQueryHandler->handle($query);
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
        $this->addTaskCommandHandler->handle($command);
        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function show(int $taskId): JsonResponse
    {
        $query = new FindTaskQuery($taskId);
        $task = $this->findTaskQueryHandler->handle($query);
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
        $this->startTaskCommandHandler->handle($command);
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
        $this->completeTaskCommandHandler->handle($command);
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
        $this->reopenTaskCommandHandler->handle($command);
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
        $this->prioritizeTaskCommandHandler->handle($command);
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
        $this->changeDeadlineTaskCommandHandler->handle($command);
        return $this->respondUpdated(
            message: 'The task was changed deadline.',
        );
    }

}
