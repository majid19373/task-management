<?php

namespace App\Repositories\Subtask;

use App\DTO\Subtask\SubtaskFilterDTO;
use App\Entities\Subtask;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

interface SubtaskRepositoryInterface
{
    public function list(SubtaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection;

    public function listWithPaginate(SubtaskFilterDTO $filters, array $select = ['*'], array $relations = []): PaginatedResult;

    public function getById(int $id, array $select = ['*'], array $relations = []): Subtask;

    public function store(Subtask $data): void;

    public function update(Subtask $data): void;

}
