<?php

namespace App\Services;

use App\DTO\Board\{BoardFilterDTO, NewBoardDTO};
use App\Entities\Board;
use App\Http\Resources\Board\BoardResource;
use App\Repositories\Board\BoardRepositoryInterface;
use App\ValueObjects\Board\{BoardName, BoardDescription};
use Exception;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

final readonly class BoardService
{
    public function __construct(
        private BoardRepositoryInterface $boardRepository
    )
    {}

    public function list(BoardFilterDTO $boardFilterDTO): Collection|PaginatedResult
    {
        if($boardFilterDTO->isPaginated){
            $result = $this->boardRepository->getWithPaginate($boardFilterDTO->perPage, BoardResource::JSON_STRUCTURE);
        }else{
            $result = $this->boardRepository->all(BoardResource::JSON_STRUCTURE);
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function create(NewBoardDTO $newBoardDTO): void
    {
        $board = $this->makeEntityForCreate($newBoardDTO);
        $this->boardRepository->store($board);
    }

    /**
     * @throws Exception
     */
    public function findById(int $boardId): Board
    {
        return $this->boardRepository->findOrFailedById($boardId, BoardResource::JSON_STRUCTURE);
    }

    private function makeEntityForCreate(NewBoardDTO $newBoardDTO): Board
    {
        return new Board(
            name: new BoardName($newBoardDTO->name),
            userId: (int)$newBoardDTO->userId,
            description: $newBoardDTO->description ? new BoardDescription($newBoardDTO->description) : null,
        );
    }
}
