<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilterDTO;
use App\Entities\Task;
use App\Models\Task as Model;
use App\Repositories\PaginatedResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    public function all(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection
    {
        $query = $this->model->query()->select($select)->with($relations);
        $tasks = $this->applyFilters($query, $filters)->get();
        return $tasks->map(function (Model $task) {
            return $this->makeEntity($task);
        });

    }

    public function getWithPaginate(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): PaginatedResult
    {
        $query = $this->model->query()->select($select)->with($relations);
        $tasks = $this->applyFilters($query, $filters)->paginate($filters->per_page);
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

    private function applyFilters(Builder $query, TaskFilterDTO $filters): Builder
    {
        return $query
            ->when($filters->status, fn($q) => $q->where('status', $filters->status))
            ->when($filters->priority, fn($q) => $q->where('priority', $filters->priority));
    }
}
