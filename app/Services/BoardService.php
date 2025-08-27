<?php

namespace App\Services;

use App\DTO\Board\{BoardFilter, NewBoard};
use App\Entities\Board;
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

    public function list(BoardFilter $boardFilter): array|PaginatedResult
    {
        if($boardFilter->isPaginated){
            $result = $this->boardRepository->getWithPaginate($boardFilter->page, $boardFilter->perPage);
        }else{
            $result = $this->boardRepository->getAll();
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function create(NewBoard $newBoard): void
    {
        $name = new BoardName($newBoard->name);
        $existsByUserIdAndName = $this->boardRepository->existsByUserIdAndName($newBoard->userId, $name);
        $board = new Board(
            existsByUserIdAndName: $existsByUserIdAndName,
            name: $name,
            userId: (int)$newBoard->userId,
            description: $newBoard->description ? new BoardDescription($newBoard->description) : null,
        );
        $this->boardRepository->store($board);
    }

    /**
     * @throws Exception
     */
    public function getById(int $boardId): Board
    {
        return $this->boardRepository->getById($boardId);
    }
}
