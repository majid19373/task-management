<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Subtask\{AddSubtaskRequest};
use Src\Application\Bus\CommandBus;
use Src\Application\CommandHandlers\Subtask\{
    AddSubtaskCommandHandler,
    CompeteSubtaskCommandHandler,
    ReopenSubtaskCommandHandler,
    StartSubtaskCommandHandler
};
use Src\Application\Commands\Subtask\{CompleteSubtaskCommand, ReopenSubtaskCommand, StartSubtaskCommand};
use Src\Application\Queries\Subtask\{ListSubtaskQuery};
use Src\Application\QueryHandlers\Subtask\{ListSubtaskQueryHandler};
use Src\Application\Bus\QueryBus;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common\Controller;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Subtask\SubtaskResource;
use Exception;
use Illuminate\Http\JsonResponse;

final class SubtaskController extends Controller
{
    public function list(int $taskId): JsonResponse
    {
        $query = new ListSubtaskQuery($taskId);

        $bus = new QueryBus(resolve(ListSubtaskQueryHandler::class));
        $subtasks = $bus->ask($query);

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

        $bus = new CommandBus(resolve(AddSubtaskCommandHandler::class));
        $bus->dispatch($command);

        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId, int $subtaskId): JsonResponse
    {
        $command = new StartSubtaskCommand($taskId, $subtaskId);

        $bus = new CommandBus(resolve(StartSubtaskCommandHandler::class));
        $bus->dispatch($command);

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

        $bus = new CommandBus(resolve(CompeteSubtaskCommandHandler::class));
        $bus->dispatch($command);

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

        $bus = new CommandBus(resolve(ReopenSubtaskCommandHandler::class));
        $bus->dispatch($command);

        return $this->respondUpdated(
            message: 'The subtask was reopened.',
        );
    }

}
