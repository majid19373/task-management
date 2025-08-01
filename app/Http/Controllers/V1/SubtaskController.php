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

}
