<?php

namespace Tests\Doubles\Repositories;

use Exception;
use Illuminate\Support\Str;
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Application\Repositories\PaginatedResult;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardName;

class FakeBoardRepository implements BoardRepositoryInterface
{
    /**
     * @var array<Board>
     * */
    private array $boards = [];
    private ?string $nextIdentity = null;

    public function getAll(int $userId): array
    {
        return array_filter($this->boards, function (Board $board) use ($userId) {
            return $board->getUserId() === $userId;
        });
    }

    public function getWithPaginate(int $userId, int $page, int $perPage): PaginatedResult
    {
        $offset = ($page - 1) * $perPage;
        $boards = $this->getAll($userId);
        $total = count($boards);
        $items = array_slice($boards, $offset, $perPage);
        return new PaginatedResult(
            $items,
            [
                'total' => $total,
                'current_page' => $page,
                'limit' => $perPage
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function getById(string $id): Board
    {
        $board = array_find($this->boards, fn($board) => $board->getId() === $id);
        if (!$board) {
            throw new Exception('The board not found.');
        }
        return $board;
    }

    public function store(Board $board): void
    {
        $this->boards[] = $board;
    }

    public function existsByUserIdAndName(int $userId, BoardName $name): bool
    {
        return array_any($this->boards, fn($board) => $board->getUserId() === $userId && $board->getName()->value() === $name->value());
    }

    public function getNextIdentity(): string
    {
        if($this->nextIdentity) {
            return $this->nextIdentity;
        }
        return Str::ulid();
    }

    public function giveAsNextIdentity(string $id): void
    {
        $this->nextIdentity = $id;
    }
}
