<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Board\BoardListFilterRequest;
use App\Http\Requests\Board\CreateBoardRequest;
use App\Http\Resources\Board\BoardResource;
use App\Services\BoardService;
use Illuminate\Http\JsonResponse;
use Exception;

final class BoardController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {}

    public function getList(BoardListFilterRequest $request): JsonResponse
    {
        $boardFilterDTO = $request->makeDTO();
        $boards = $this->boardService->getList($boardFilterDTO);
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
        $board = $this->boardService->findById($boardId);
        return $this->respond(
            data: BoardResource::toArray($board->data),
        );
    }
}
