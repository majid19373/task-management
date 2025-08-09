<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilterDTO;
use App\Entities\Task;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function list(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection;

    public function listWithPaginate(
        TaskFilterDTO $filters,
        array $select = ['*'],
        array $relations = []
    ): PaginatedResult;

    public function getById(int $id, array $select = ['*'], array $relations = []): Task;

    public function getByIdIfSubtasksAreCompleted(int $id, array $select = ['*']): Task;

    public function store(Task $data): void;

    public function update(Task $data): void;

}
