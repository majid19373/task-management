<?php

namespace App\Repositories\Task;

use App\DTO\Subtask\SubtaskFilterDTO;
use App\DTO\Task\TaskFilterDTO;
use App\Entities\Subtask;
use App\Entities\Task;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function taskList(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection;

    public function taskListWithPaginate(
        TaskFilterDTO $filters,
        array $select = ['*'],
        array $relations = []
    ): PaginatedResult;

    public function subtaskList(SubtaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection;

    public function subtaskListWithPaginate(SubtaskFilterDTO $filters, array $select = ['*'], array $relations = []): PaginatedResult;

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Task;

    public function isExist(int $id): bool;

    public function storeTask(Task $data): void;
    public function storeSubTask(Subtask $data): void;

    public function update(Task $data): void;

}
