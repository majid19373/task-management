<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Subtask\{AddSubtaskRequest};
use Src\Application\Bus\CommandBus;
use Src\Application\Commands\Subtask\{CompleteSubtaskCommand,
    RemoveSubtaskCommand,
    ReopenSubtaskCommand,
    StartSubtaskCommand};
use Src\Application\Queries\Subtask\{ListSubtaskQuery};
use Src\Application\Bus\QueryBus;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common\Controller;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Subtask\SubtaskResource;
use Exception;
use Illuminate\Http\JsonResponse;

final class SubtaskController extends Controller
{
    public function __construct(
        private readonly QueryBus   $queryBus,
        private readonly CommandBus $commandBus,
    )
    {}

    public function list(int $taskId): JsonResponse
    {
        $query = new ListSubtaskQuery($taskId);

        $subtasks = $this->queryBus->ask($query);

        return $this->respond(
            data: SubtaskResource::toArrayList($subtasks),
        );
    }

    /**
     * @throws Exception
     */
    public function add(AddSubtaskRequest $request): JsonResponse
    {
        $command = $request->makeDTO();

        $this->commandBus->dispatch($command);

        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId, int $subtaskId): JsonResponse
    {
        $command = new StartSubtaskCommand($taskId, $subtaskId);

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The subtask was started.',
        );
    }

    /**
     * @throws Exception
     */
    public function complete(int $taskId, int $subtaskId): JsonResponse
    {
        $command = new CompleteSubtaskCommand($taskId, $subtaskId);

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The subtask was completed.',
        );
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId, int $subtaskId): JsonResponse
    {
        $command = new ReopenSubtaskCommand($taskId, $subtaskId);

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The subtask was reopened.',
        );
    }

    /**
     * @throws Exception
     */
    public function remove(int $taskId, int $subtaskId): JsonResponse
    {
        $command = new RemoveSubtaskCommand($taskId, $subtaskId);

        $this->commandBus->dispatch($command);

        return $this->respondUpdated(
            message: 'The subtask was removed.',
        );
    }

}
