<?php

namespace Src\Application\Contracts\Repositories;

use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Domain\Task\Task;
use Src\Application\Contracts\Repositories\PaginatedResult;

interface TaskRepositoryInterface
{
    public function list(ListTaskQuery $filters): array;

    public function listWithPaginate(
        PaginateTaskQuery $filters,
    ): PaginatedResult;

    public function getById(int $id): Task;

    public function getBySubtaskId(int $id): Task;

    public function store(Task $task): void;
    public function getNextIdentity(): int;
    public function getNextSubtaskIdentity(): int;

}
