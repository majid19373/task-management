<?php

namespace App\Http\Controllers\V1;

use App\DTO\Board\BoardDTO;
use App\DTO\Board\BoardFilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Resources\Board\BoardResource;
use App\Services\BoardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
                paginate: $boards->data,
                data: BoardResource::collection($boards->data),
            );
        }
        return $this->respond(
            data: new BoardResource($boards->data),
        );
    }

    public function store(StoreBoardRequest $request): JsonResponse
    {
        $boardDTO = BoardDTO::make($request->validated());
        $board = $this->boardService->store($boardDTO);
        if(!$board->success){
            return $this->respondException(
                message: $board->message,
                statusCode: $board->statusCode
            );
        }
        return $this->respondCreated(
            data: new BoardResource($board->data),
        );
    }

    public function show(int $boardId): JsonResponse
    {
        $board = $this->boardService->findById($boardId);
        if(!$board->success){
            return $this->respondException(
                message: $board->message,
                statusCode: $board->statusCode
            );
        }
        return $this->respond(
            data: new BoardResource($board->data),
        );
    }
}
