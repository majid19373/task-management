<?php

namespace App\Services;

use App\DTO\Board\{BoardFilterDTO, NewBoardDTO};
use App\DTO\ServicesResultDTO;
use App\Entities\Board;
use App\Http\Resources\Board\BoardResource;
use App\Repositories\Board\BoardRepositoryInterface;
use App\ValueObjects\Board\{BoardName, BoardDescription};
use Exception;

final class BoardService extends BaseService
{
    public function __construct(
        private readonly BoardRepositoryInterface $boardRepository
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
    public function store(NewBoardDTO $newBoardDTO): ServicesResultDTO
    {
        $board = $this->makeEntity($newBoardDTO);
        $this->boardRepository->create($board);
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

    private function makeEntity(NewBoardDTO $newBoardDTO): Board
    {
        return new Board(
            name: new BoardName($newBoardDTO->name),
            userId: (int)$newBoardDTO->user_id,
            description: new BoardDescription($newBoardDTO->description)
        );
    }
}
