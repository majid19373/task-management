<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected Model $model;

    public function all(array $select = ['*'], array $relations = []): Collection
    {
        return $this->model->query()->select($select)->with($relations)->get();
    }

    public function getWithPaginate(int $perPage, array $select = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->query()->select($select)->with($relations)->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->query()->create($data);
    }

    public function findById(int $id, array $select = ['*'], array $relations = [])
    {
        return $this->model->query()->select($select)->with($relations)->find($id);
    }

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = [])
    {
        return $this->model->query()->select($select)->with($relations)->findOrFail($id);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->findOrFailedById($id);
        return $record->update($data);
    }

    public function updateWithModel(Model $model, array $data): bool
    {
        return $model->update($data);
    }

}
