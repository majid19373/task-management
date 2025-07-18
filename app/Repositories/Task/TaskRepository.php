<?php

namespace App\Repositories\Task;

use App\Entities\Task;
use App\Models\Task as Model;
use App\Repositories\PaginatedResult;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Exception;

final class TaskRepository
{
    private Model $model;
    public function __construct(
        Model $model
    ){
        $this->model = $model;
    }

    public function all(array $select = ['*'], array $relations = []): Collection
    {
        $tasks = $this->model->query()->select($select)->with($relations)->get();
        return $tasks->map(function (Model $task) {
            return $this->makeEntity($task);
        });

    }

    public function getWithPaginate(int $perPage, array $select = ['*'], array $relations = []): PaginatedResult
    {
        $tasks = $this->model->query()->select($select)->with($relations)->paginate($perPage);
        $all = $tasks->map(function (Model $task) {
            return $this->makeEntity($task);
        });
        return PaginatedResult::make(
            list: $all,
            paginator: [
                'total' => $tasks->total(),
                'limit' => $tasks->perPage(),
                'current_page' => $tasks->currentPage(),
            ]
        );
    }

    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Task
    {
        $task = $this->model->query()->select($select)->with($relations)->findOrFail($id);
        return $this->makeEntity($task);
    }

    /**
     * @throws Exception
     */
    public function store(Task $data): int
    {
        $task = $this->model->query()->create([
            'title' => $data->getTitle(),
            'board_id' => $data->getBoardId(),
            'description' => $data->getDescription(),
            'deadline' => $data->getDeadline(),
        ]);
        if(!$task){
            throw new Exception('Task not created');
        }
        return $task->id;
    }

    /**
     * @throws Exception
     */
    public function update(Task $data): bool
    {
        $task = $this->model->query()->findOrFail($data->getId())->update([
            'description' => $data->getDescription(),
            'deadline' => $data->getDeadline(),
            'status' => $data->getStatus(),
            'priority' => $data->getPriority(),
        ]);
        if(!$task){
            throw new Exception('Task not created');
        }
        return $task;
    }

    private function makeEntity(Model $task): Task
    {
        return new Task(
            id: (int)$task->id,
            title: $task->title,
            boardId: (int)$task->board_id,
            description: $task->description,
            status: $task->status,
            priority: $task->priority,
            deadline: $task->deadline ? Carbon::make($task->deadline) : null,
        );
    }
}
