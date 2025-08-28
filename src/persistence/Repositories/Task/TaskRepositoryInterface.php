<?php

namespace Src\persistence\Repositories\Task;

use Src\application\DTO\Task\TaskFilter;
use Src\domain\Entities\Task\Task;
use Src\persistence\Repositories\PaginatedResult;

interface TaskRepositoryInterface
{
    public function list(TaskFilter $filters): array;

    public function listWithPaginate(
        TaskFilter $filters,
    ): PaginatedResult;

    public function getById(int $id): Task;

    public function getBySubtaskId(int $id): Task;

    public function store(Task $task): void;

}
