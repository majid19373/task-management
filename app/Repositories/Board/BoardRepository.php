<?php

namespace App\Repositories\Board;

use App\Entities\Board;
use App\Models\Board as Model;
use App\Repositories\PaginatedResult;
use Exception;
use Illuminate\Support\Collection;

final class BoardRepository implements BoardInterface
{
    private Model $model;
    public function __construct(
        Model $model
    ){
        $this->model = $model;
    }

    public function all(array $select = ['*'], array $relations = []): Collection
    {
        $boards = $this->model->query()->select($select)->with($relations)->get();
        return $boards->map(function (Model $board) {
            return $this->makeEntity($board);
        });

    }

    public function getWithPaginate(int $perPage, array $select = ['*'], array $relations = []): PaginatedResult
    {
        $boards = $this->model->query()->select($select)->with($relations)->paginate($perPage);
        $all = $boards->map(function (Model $board) {
            return $this->makeEntity($board);
        });
        return PaginatedResult::make(
            list: $all,
            paginator: [
                'total' => $boards->total(),
                'limit' => $boards->perPage(),
                'current_page' => $boards->currentPage(),
            ]
        );
    }

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Board
    {
        $board = $this->model->query()->select($select)->with($relations)->findOrFail($id);
        return $this->makeEntity($board);
    }

    /**
     * @throws Exception
     */
    public function store(Board $data): int
    {
        $board = $this->model->query()->create([
            'name' => $data->getName(),
            'user_id' => $data->getUserId(),
            'description' => $data->getDescription(),
        ]);
        if(!$board){
            throw new Exception('Board not created');
        }
        return $board->id;
    }

    private function makeEntity(Model $board): Board
    {
        return new Board(
            id: (int)$board->id,
            name: $board->name,
            userId: (int)$board->user_id,
            description: $board->description
        );
    }
}
