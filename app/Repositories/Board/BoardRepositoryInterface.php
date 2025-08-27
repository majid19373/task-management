<?php

namespace App\Repositories\Board;

use App\Entities\Board;
use App\Repositories\PaginatedResult;
use App\ValueObjects\Board\BoardName;

interface BoardRepositoryInterface
{
    public function getAll(): array;

    public function getWithPaginate(int $page, int $perPage): PaginatedResult;

    public function getById(int $id): Board;

    public function store(Board $board): void;

    public function existsByUserIdAndName(int $userId, BoardName $name): bool;
}
