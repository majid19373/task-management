<?php

namespace App\Services;

use App\DTO\Board\BoardDTO;
use App\DTO\Board\BoardFilterDTO;
use App\DTO\ServicesResultDTO;
use App\Http\Resources\Board\BoardResource;
use App\Repositories\BoardRepository;
use Illuminate\Http\Response as Res;

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

    public function store(BoardDTO $boardDTO): ServicesResultDTO
    {
        $result = $this->boardRepository->store($boardDTO->toArray(), BoardResource::JSON_STRUCTURE);
        if (!$result) {
            return $this->errorResult(
                message: 'User not Found.',
                statusCode: Res::HTTP_UNPROCESSABLE_ENTITY,
            );
        }
        return $this->successResult(
            data: $result,
        );
    }

    public function findById(int $boardId): ServicesResultDTO
    {
        $result = $this->boardRepository->findById($boardId, BoardResource::JSON_STRUCTURE);
        if (is_null($result)) {
            return $this->errorResult(
                message: 'User not Found.',
                statusCode: Res::HTTP_NOT_FOUND,
            );
        }
        return $this->successResult(
            data: $result,
        );
    }
}
