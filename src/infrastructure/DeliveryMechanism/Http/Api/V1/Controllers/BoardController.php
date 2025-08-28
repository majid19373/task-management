<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board\BoardListFilterRequest;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board\CreateBoardRequest;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Board\BoardResource;
use Src\application\Services\BoardService;
use Illuminate\Http\JsonResponse;
use Exception;

final class BoardController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {}

    public function list(BoardListFilterRequest $request): JsonResponse
    {
        $boardFilterDTO = $request->makeDTO();
        $boards = $this->boardService->list($boardFilterDTO);
        if($boardFilterDTO->isPaginated){
            return $this->respondWithPagination(
                paginate: $boards->paginator,
                data: BoardResource::toArrayList($boards->list),
            );
        }
        return $this->respond(
            data: BoardResource::toArrayList($boards),
        );
    }

    /**
     * @throws Exception
     */
    public function create(CreateBoardRequest $request): JsonResponse
    {
        $boardDTO = $request->makeDTO();
        $this->boardService->create($boardDTO);
        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function show(int $boardId): JsonResponse
    {
        $board = $this->boardService->getById($boardId);
        return $this->respond(
            data: BoardResource::toArray($board),
        );
    }
}
