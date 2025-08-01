<?php

namespace App\Repositories\Board;

use App\Entities\Board;
use App\Models\Board as Model;
use App\Repositories\PaginatedResult;
use App\Repositories\ReflectionEntityWithoutConstructor;
use App\ValueObjects\Board\{BoardDescription, BoardName};
use Exception;
use Illuminate\Support\Collection;
use ReflectionException;

final class BoardRepository implements BoardRepositoryInterface
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

    /**
     * @throws ReflectionException
     */
    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Board
    {
        $board = $this->model->query()->select($select)->with($relations)->findOrFail($id);
        return $this->makeEntity($board);
    }

    public function isExist(int $id): bool
    {
        return $this->model->query()->where('id', '=', $id)->exists();
    }

    /**
     * @throws Exception
     */
    public function store(Board $data): void
    {
        $board = $this->model->query()->create([
            'name' => $data->getName()->getName(),
            'user_id' => $data->getUserId(),
            'description' => $data->getDescription()?->value(),
        ]);
        if(!$board){
            throw new Exception('Board not created');
        }
        $data->setId($board->id);
    }

    /**
     * @throws ReflectionException
     */
    private function makeEntity(Model $data): Board
    {
        $reflection = new ReflectionEntityWithoutConstructor(Board::class);

        $reflection->setValueInProperty('id', (int)$data->id);

        $reflectionName = new ReflectionEntityWithoutConstructor(BoardName::class);
        $reflectionName->setValueInProperty('name', $data->name);
        $reflection->setValueInProperty('name', $reflectionName->getEntity());

        $description = $data->description;
        if($data->description){
            $reflectionDescription = new ReflectionEntityWithoutConstructor(BoardDescription::class);
            $reflectionDescription->setValueInProperty('description', $data->description);
            $description = $reflectionDescription->getEntity();
        }
        $reflection->setValueInProperty('description', $description);

        return $reflection->getEntity();
    }
}
