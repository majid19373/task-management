<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilterDTO;
use App\Entities\SubTask;
use App\Entities\Task;
use App\Enums\TaskStatusEnum;
use App\Models\Task as Model;
use App\Repositories\PaginatedResult;
use App\Repositories\ReflectionEntityWithoutConstructor;
use App\ValueObjects\Task\TaskDeadline;
use App\ValueObjects\Task\TaskDescription;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskTitle;
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
        $tasks = $this->applyFilters($query, $filters)->paginate($filters->perPage);
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

    /**
     * @throws ReflectionException
     */
    public function findOrFailedById(int $id, array $select = ['*'], array $relations = []): Task
    {
        $task = $this->model->query()->select($select)->with($relations)->findOrFail($id);
        return $this->makeEntity($task);
    }

    public function isExist(int $id): bool
    {
        return $this->model->query()
            ->where('id', '=', $id)
            ->where('status', '<>', TaskStatusEnum::COMPLETED->value)
            ->exists();
    }

    /**
     * @throws Exception
     */
    public function storeTask(Task $data): void
    {
        $task = $this->model->query()->create([
            'title' => $data->getTitle()->value(),
            'board_id' => $data->getBoardId(),
            'description' => $data->getDescription()?->value(),
            'deadline' => $data->getDeadline()?->value(),
            'status' => $data->getStatus()->value(),
            'priority' => $data->getPriority()->value(),
        ]);
        if(!$task){
            throw new Exception('Task not created');
        }
        $data->setId($task->id);
    }

    /**
     * @throws Exception
     */
    public function storeSubTask(SubTask $data): void
    {
        $task = $this->model->query()->create([
            'title' => $data->getTitle()->value(),
            'parent_id' => $data->getTaskId(),
            'board_id' => $data->getBoardId(),
            'description' => $data->getDescription()->value(),
            'deadline' => $data->getDeadline()->value(),
            'status' => $data->getStatus()->value(),
            'priority' => $data->getPriority()->value(),
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
            'status' => $data->getStatus()->value(),
            'priority' => $data->getPriority()->value(),
        ]);
        if(!$task){
            throw new Exception('Task not updated');
        }
    }

    private function applyFilters(Builder $query, TaskFilterDTO $filters): Builder
    {
        return $query
            ->when($filters->status, fn($q) => $q->where('status', $filters->status))
            ->when($filters->priority, fn($q) => $q->where('priority', $filters->priority));
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function makeEntity(Model $data): Task
    {
        $reflection = new ReflectionEntityWithoutConstructor(Task::class);

        $reflection->setValueInProperty('id', (int)$data->id);

        $reflection->setValueInProperty('boardId', (int)$data->board_id);

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

        $reflectionStatus = new ReflectionEntityWithoutConstructor(TaskStatus::class);
        $reflectionStatus->setValueInProperty('status', $data->status);
        $reflection->setValueInProperty('status', $reflectionStatus->getEntity());

        $reflectionPriority = new ReflectionEntityWithoutConstructor(TaskPriority::class);
        $reflectionPriority->setValueInProperty('priority', $data->priority);
        $reflection->setValueInProperty('priority', $reflectionPriority->getEntity());

        $reflection->setValueInProperty('deadline', $data->deadline ? new TaskDeadline($data->deadline) : null);

        return $reflection->getEntity();
    }
}
