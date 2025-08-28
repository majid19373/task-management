<?php

namespace Src\application\Services;

use Src\application\DTO\Board\{BoardFilter};
use Src\domain\Entities\Board\Board;
use Src\domain\Entities\Board\ValueObjects\BoardDescription;
use Src\persistence\Repositories\Board\BoardRepositoryInterface;
use Src\domain\Entities\Board\ValueObjects\{BoardName};
use Exception;
use Src\persistence\Repositories\PaginatedResult;
use Src\application\DTO\Board\NewBoard;

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
