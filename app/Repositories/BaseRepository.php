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

    public function store(array $data, array $select = ['*'], array $relations = [])
    {
        $record =  $this->model->query()->create($data);
        return $this->findById($record->id, $select, $relations);
    }

    public function findById(int $id, array $select = ['*'], array $relations = [])
    {
        return $this->model->query()->select($select)->with($relations)->find($id);
    }

}
