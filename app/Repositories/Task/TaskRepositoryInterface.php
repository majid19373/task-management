<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilterDTO;
use App\Entities\SubTask;
use App\Entities\Task;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function all(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection;

    public function getWithPaginate(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): PaginatedResult;

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Task;

    public function isExist(int $id): bool;

    public function storeTask(Task $data): void;
    public function storeSubTask(SubTask $data): void;

    public function update(Task $data): void;

}
