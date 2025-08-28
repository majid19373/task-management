<?php

namespace Src\persistence\Repositories\Board;

use Src\domain\Entities\Board\Board;
use Src\persistence\Repositories\PaginatedResult;
use Src\domain\Entities\Board\ValueObjects\BoardName;

interface BoardRepositoryInterface
{
    public function getAll(): array;

    public function getWithPaginate(int $page, int $perPage): PaginatedResult;

    public function getById(int $id): Board;

    public function store(Board $board): void;

    public function existsByUserIdAndName(int $userId, BoardName $name): bool;
}
