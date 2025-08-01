<?php

namespace App\Repositories\Board;

use App\Entities\Board;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface BoardRepositoryInterface
{
    public function all(array $select = ['*'], array $relations = []): Collection;

    public function getWithPaginate(int $perPage, array $select = ['*'], array $relations = []): PaginatedResult;

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Board;

    public function isExist(int $id): bool;

    public function store(Board $data): void;
}
