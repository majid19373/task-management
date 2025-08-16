<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subtask\{AddSubtaskRequest, FilterSubtaskRequest};
use App\Http\Resources\Subtask\SubtaskResource;
use App\Services\SubtaskService;
use Exception;
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
        $tasks = $this->subtaskService->list($subtaskFilterDTO);
        if($subtaskFilterDTO->isPaginated){
            return $this->respondWithPagination(
                paginate: $tasks->paginator,
                data: SubtaskResource::toArrayList($tasks->list),
            );
        }
        return $this->respond(
            data: SubtaskResource::toArrayList($tasks),
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
    public function start(int $subtaskId): JsonResponse
    {
        $this->subtaskService->start($subtaskId);
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
