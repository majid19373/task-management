<?php

namespace App\Http\Controllers\V1;

use App\DTO\Board\BoardDTO;
use App\DTO\Board\BoardFilterDTO;
use App\DTO\Board\NewBoardDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Resources\Board\BoardResource;
use App\Services\BoardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

final class BoardController extends Controller
{
    public function __construct(
        private readonly BoardService $boardService,
    )
    {}

    public function index(Request $request): JsonResponse
    {
        $boardFilterDTO = BoardFilterDTO::make($request->toArray());
        $boards = $this->boardService->index($boardFilterDTO);
        if($boardFilterDTO->is_paginated){
            return $this->respondWithPagination(
                paginate: $boards->data->paginator,
                data: BoardResource::toArrayList($boards->data->list),
            );
        }
        return $this->respond(
            data: BoardResource::toArrayList($boards->data),
        );
    }

    /**
     * @throws Exception
     */
    public function store(StoreBoardRequest $request): JsonResponse
    {
        $boardDTO = NewBoardDTO::make($request->validated());
        $board = $this->boardService->store($boardDTO);
        return $this->respondCreated(
            data: BoardResource::toArray($board->data),
        );
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
