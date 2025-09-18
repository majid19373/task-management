<?php

namespace Src\Application\Repositories;

use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Domain\Task\Task;
use Src\Application\Repositories\PaginatedResult;

interface TaskRepositoryInterface
{
    public function list(ListTaskQuery $filters): array;

    public function listWithPaginate(
        PaginateTaskQuery $filters,
    ): PaginatedResult;

    public function getById(string $id): Task;

    public function getBySubtaskId(string $id): Task;

    public function store(Task $task): void;
    public function getNextIdentity(): string;

}
