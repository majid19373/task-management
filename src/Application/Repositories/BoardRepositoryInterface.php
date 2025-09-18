<?php

namespace Src\Application\Repositories;

use Src\Domain\Board\Board;
use Src\Application\Repositories\PaginatedResult;
use Src\Domain\Board\BoardName;

interface BoardRepositoryInterface
{
    public function getAll(): array;

    public function getWithPaginate(int $page, int $perPage): PaginatedResult;

    public function getById(string $id): Board;

    public function store(Board $board): void;

    public function existsByUserIdAndName(int $userId, BoardName $name): bool;

    public function getNextIdentity(): string;
}
