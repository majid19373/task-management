<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Subtask\{AddSubtaskRequest};
use Src\Application\CommandHandlers\Subtask\AddSubtaskCommandHandler;
use Src\Application\CommandHandlers\Subtask\CompeteSubtaskCommandHandler;
use Src\Application\CommandHandlers\Subtask\ReopenSubtaskCommandHandler;
use Src\Application\CommandHandlers\Subtask\StartSubtaskCommandHandler;
use Src\Application\Commands\Subtask\CompleteSubtaskCommand;
use Src\Application\Commands\Subtask\ReopenSubtaskCommand;
use Src\Application\Commands\Subtask\StartSubtaskCommand;
use Src\Application\Queries\Subtask\ListSubtaskQuery;
use Src\Application\QueryHandlers\Subtask\ListSubtaskQueryHandler;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common\Controller;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Subtask\SubtaskResource;
use Exception;
use Illuminate\Http\JsonResponse;

final class SubtaskController extends Controller
{
    public function __construct(
        private readonly ListSubtaskQueryHandler      $listSubtaskQueryHandler,
        private readonly AddSubtaskCommandHandler     $addSubtaskCommandHandler,
        private readonly StartSubtaskCommandHandler   $startSubtaskCommandHandler,
        private readonly CompeteSubtaskCommandHandler $competeSubtaskCommandHandler,
        private readonly ReopenSubtaskCommandHandler  $reopenSubtaskCommandHandler,
    )
    {}

    public function list(int $taskId): JsonResponse
    {
        $query = new ListSubtaskQuery($taskId);
        $subtasks = $this->listSubtaskQueryHandler->handle($query);
        return $this->respond(
            data: SubtaskResource::toArrayList($subtasks),
        );
    }

    /**
     * @throws Exception
     */
    public function add(AddSubtaskRequest $request): JsonResponse
    {
        $subtaskDTO = $request->makeDTO();
        $this->addSubtaskCommandHandler->handle($subtaskDTO);
        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId, int $subtaskId): JsonResponse
    {
        $command = new StartSubtaskCommand($taskId, $subtaskId);
        $this->startSubtaskCommandHandler->handle($command);
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
        $this->competeSubtaskCommandHandler->handle($command);
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
        $this->reopenSubtaskCommandHandler->handle($command);
        return $this->respondUpdated(
            message: 'The subtask was reopened.',
        );
    }

}
