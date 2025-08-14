<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilter;
use App\Entities\Task;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function list(TaskFilter $filters, array $select = ['*'], array $relations = []): Collection;

    public function listWithPaginate(
        TaskFilter $filters,
        array      $select = ['*'],
        array      $relations = []
    ): PaginatedResult;

    public function getById(int $id, array $select = ['*'], array $relations = []): Task;

    public function getByIdIfSubtasksAreCompleted(int $id, array $select = ['*']): Task;

    public function store(Task $data): void;

    public function update(Task $data): void;

}
