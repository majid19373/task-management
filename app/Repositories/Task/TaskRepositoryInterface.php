<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilter;
use App\Entities\Task;
use App\Repositories\PaginatedResult;

interface TaskRepositoryInterface
{
    public function list(TaskFilter $filters): array;

    public function listWithPaginate(
        TaskFilter $filters,
    ): PaginatedResult;

    public function getById(int $id): Task;

    public function getBySubtaskId(int $id): Task;


    public function store(Task $task): void;

    public function update(Task $task): void;

}
