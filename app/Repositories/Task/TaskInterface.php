<?php

namespace App\Repositories\Task;

use App\Entities\Task;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface TaskInterface
{
    public function all(array $select = ['*'], array $relations = []): Collection;

    public function getWithPaginate(int $perPage, array $select = ['*'], array $relations = []): PaginatedResult;

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Task;

    public function store(Task $data): int;

}
