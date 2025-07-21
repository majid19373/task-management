<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilterDTO;
use App\Entities\Task;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function all(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection;

    public function getWithPaginate(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): PaginatedResult;

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Task;

    public function create(Task $data): void;

    public function update(Task $data): void;

}
