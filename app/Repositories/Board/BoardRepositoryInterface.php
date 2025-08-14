<?php

namespace App\Repositories\Board;

use App\Entities\Board;
use App\Repositories\PaginatedResult;
use App\ValueObjects\Board\BoardName;
use Illuminate\Support\Collection;

interface BoardRepositoryInterface
{
    public function getAll(array $select = ['*'], array $relations = []): Collection;

    public function getWithPaginate(int $perPage, array $select = ['*'], array $relations = []): PaginatedResult;

    public function getById(int $id, array $select = ['*'], array $relations = []): Board;

    public function store(Board $data): void;

    public function existsByUserIdAndName(int $userId, BoardName $name): bool;
}
