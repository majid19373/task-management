<?php

namespace App\Services;

use App\DTO\Board\BoardDTO;
use App\DTO\Board\BoardFilterDTO;
use App\DTO\ServicesResultDTO;
use App\Entities\Board;
use App\Http\Resources\Board\BoardResource;
use App\Repositories\Board\BoardRepository;
use Exception;

final class BoardService extends BaseService
{
    public function __construct(
        private readonly BoardRepository $boardRepository
    )
    {}

    public function index(BoardFilterDTO $boardFilterDTO): ServicesResultDTO
    {
        if($boardFilterDTO->is_paginated){
            $result = $this->boardRepository->getWithPaginate($boardFilterDTO->per_page, BoardResource::JSON_STRUCTURE);
        }else{
            $result = $this->boardRepository->all(BoardResource::JSON_STRUCTURE);
        }
        return $this->successResult(
            data: $result,
        );
    }

    /**
     * @throws Exception
     */
    public function store(BoardDTO $boardDTO): ServicesResultDTO
    {
        $board = $this->makeEntity($boardDTO);
        $boardId = $this->boardRepository->store($board);
        $board = $this->boardRepository->findOrFailedById($boardId, BoardResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $board,
        );
    }

    /**
     * @throws Exception
     */
    public function findById(int $boardId): ServicesResultDTO
    {
        $board = $this->boardRepository->findOrFailedById($boardId, BoardResource::JSON_STRUCTURE);
        return $this->successResult(
            data: $board,
        );
    }

    private function makeEntity(BoardDTO $boardDTO): Board
    {
        return new Board(
            id: (int)$boardDTO->id ?? 0,
            name: $boardDTO->name,
            userId: (int)$boardDTO->user_id,
            description: $boardDTO->description
        );
    }
}
