<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Subtask\{AddSubtaskRequest};
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Subtask\SubtaskResource;
use Src\application\Services\SubtaskService;
use Exception;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Subtask\FilterSubtaskRequest;
use Illuminate\Http\JsonResponse;

final class SubtaskController extends Controller
{
    public function __construct(
        private readonly SubtaskService $subtaskService,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(FilterSubtaskRequest $request): JsonResponse
    {
        $subtaskFilterDTO = $request->makeDTO();
        $subtasks = $this->subtaskService->list($subtaskFilterDTO);
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
        $this->subtaskService->add($subtaskDTO);
        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId, int $subtaskId): JsonResponse
    {
        $this->subtaskService->start($taskId, $subtaskId);
        return $this->respondUpdated(
            message: 'The subtask was started.',
        );
    }

    /**
     * @throws Exception
     */
    public function complete(int $taskId): JsonResponse
    {
        $this->subtaskService->complete($taskId);
        return $this->respondUpdated(
            message: 'The subtask was completed.',
        );
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): JsonResponse
    {
        $this->subtaskService->reopen($taskId);
        return $this->respondUpdated(
            message: 'The subtask was reopened.',
        );
    }

}
