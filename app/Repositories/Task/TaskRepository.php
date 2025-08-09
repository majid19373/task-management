<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilterDTO;
use App\Entities\Task;
use App\Models\Task as Model;
use App\Repositories\PaginatedResult;
use App\Repositories\ReflectionEntityWithoutConstructor;
use App\ValueObjects\Subtask\SubtaskStatus;
use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskStatus, TaskPriority, TaskTitle};
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ReflectionException;

final class TaskRepository implements TaskRepositoryInterface
{
    private Model $model;
    public function __construct(
        Model $model
    ){
        $this->model = $model;
    }

    public function list(TaskFilterDTO $filters, array $select = ['*'], array $relations = []): Collection
    {
        $query = $this->model->query()->select($select)->with($relations);
        $tasks = $this->applyTaskFilters($query, $filters)->get();
        return $tasks->map(function (Model $task) {
            return $this->makeEntityForTask($task);
        });

    }

    public function listWithPaginate(
        TaskFilterDTO $filters,
        array $select = ['*'],
        array $relations = []
    ): PaginatedResult
    {
        $query = $this->model->query()->select($select)->with($relations)
            ->where('board_id', '=', $filters->boardId);
        $tasks = $this->applyTaskFilters($query, $filters)->paginate($filters->perPage);
        $all = $tasks->map(function (Model $task) {
            return $this->makeEntityForTask($task);
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

    /**
     * @throws ReflectionException
     */
    public function getById(int $id, array $select = ['*'], array $relations = []): Task
    {
        $task = $this->model->query()->select($select)->with($relations)->findOrFail($id);
        return $this->makeEntityForTask($task);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getByIdIfSubtasksAreCompleted(int $id, array $select = ['*']): Task
    {
        $task = $this->model->query()
            ->select($select)
            ->with(['subtasks' => function ($query) {
                $query->where('status', '<>', SubtaskStatus::COMPLETED);
            }])
            ->where('id', '=', $id)
            ->first();
        if (!$task) {
            throw new Exception('Task cannot be completed.');
        }
        return $this->makeEntityForTask($task);
    }

    /**
     * @throws Exception
     */
    public function store(Task $data): void
    {
        $task = $this->model->query()->create([
            'title' => $data->getTitle()->value(),
            'board_id' => $data->getBoardId(),
            'description' => $data->getDescription()?->value(),
            'deadline' => $data->getDeadline()?->value(),
            'status' => $data->getStatus()->value,
            'priority' => $data->getPriority()->value,
        ]);
        if(!$task){
            throw new Exception('Task not created');
        }
        $data->setId($task->id);
    }

    /**
     * @throws Exception
     */
    public function update(Task $data): void
    {
        $task = $this->model->query()->findOrFail($data->getId())->update([
            'description' => $data->getDescription()?->value(),
            'deadline' => $data->getDeadline()?->value(),
            'status' => $data->getStatus()->value,
            'priority' => $data->getPriority()->value,
        ]);
        if(!$task){
            throw new Exception('Task not updated');
        }
    }

    private function applyTaskFilters(Builder $query, TaskFilterDTO $filters): Builder
    {
        return $query
            ->when($filters->status, fn($q) => $q->where('status', $filters->status))
            ->when($filters->priority, fn($q) => $q->where('priority', $filters->priority));
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function makeEntityForTask(Model $data): Task
    {
        $reflection = new ReflectionEntityWithoutConstructor(Task::class);

        $reflection->setValueInProperty('boardId', (int)$data->board_id);

        $reflection->setValueInProperty('id', (int)$data->id);

        $reflectionTitle = new ReflectionEntityWithoutConstructor(TaskTitle::class);
        $reflectionTitle->setValueInProperty('title', $data->title);
        $reflection->setValueInProperty('title', $reflectionTitle->getEntity());

        $description = $data->description;
        if($description){
            $reflectionDescription = new ReflectionEntityWithoutConstructor(TaskDescription::class);
            $reflectionDescription->setValueInProperty('description', $data->description);
            $description = $reflectionDescription->getEntity();
        }
        $reflection->setValueInProperty('description', $description);

        $reflection->setValueInProperty('status', TaskStatus::toCase($data->status));

        $reflection->setValueInProperty('priority', TaskPriority::toCase($data->priority));

        $reflection->setValueInProperty('deadline', $data->deadline ? new TaskDeadline($data->deadline) : null);

        return $reflection->getEntity();
    }
}
