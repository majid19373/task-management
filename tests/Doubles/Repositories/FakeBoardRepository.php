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

    public function getAll(): array
    {
        return $this->boards;
    }

    public function getWithPaginate(int $page, int $perPage): PaginatedResult
    {
        return new PaginatedResult(
            $this->boards,
            []
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
        return Str::ulid();
    }
}
