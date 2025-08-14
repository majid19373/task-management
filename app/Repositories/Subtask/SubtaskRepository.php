<?php

namespace App\Repositories\Subtask;

use App\DTO\Subtask\SubtaskFilter;
use App\Entities\Subtask;
use App\Models\Task as Model;
use App\Repositories\PaginatedResult;
use App\Repositories\ReflectionEntityWithoutConstructor;
use App\ValueObjects\Subtask\{SubtaskDeadline, SubtaskDescription, SubtaskPriority, SubtaskStatus, SubtaskTitle};
use Exception;
use Illuminate\Support\Collection;
use ReflectionException;

final class SubtaskRepository implements SubtaskRepositoryInterface
{
    private Model $model;
    public function __construct(
        Model $model
    ){
        $this->model = $model;
    }

    public function list(SubtaskFilter $filters, array $select = ['*'], array $relations = []): Collection
    {
        $subtasks = $this->model->query()->select($select)->with($relations)
            ->where('task_id', '=', $filters->taskId)->get();
        return $subtasks->map(function (Model $task) {
            return $this->makeEntity($task);
        });

    }

    public function listWithPaginate(
        SubtaskFilter $filters,
        array         $select = ['*'],
        array         $relations = []
    ): PaginatedResult
    {
        $subtasks = $this->model->query()->select($select)->with($relations)
            ->where('task_id', '=', $filters->taskId)->paginate($filters->perPage);
        $all = $subtasks->map(function (Model $task) {
            return $this->makeEntity($task);
        });
        return PaginatedResult::make(
            list: $all,
            paginator: [
                'total' => $subtasks->total(),
                'limit' => $subtasks->perPage(),
                'current_page' => $subtasks->currentPage(),
            ]
        );
    }

    /**
     * @throws ReflectionException
     */
    public function getById(int $id, array $select = ['*'], array $relations = []): Subtask
    {
        $task = $this->model->query()->select($select)->with($relations)->findOrFail($id);
        return $this->makeEntity($task);
    }

    /**
     * @throws Exception
     */
    public function store(Subtask $data): void
    {
        $task = $this->model->query()->create([
            'task_id' => $data->getTaskId(),
            'board_id' => $this->model->query()->find($data->getTaskId())->pluck('id')->first(),
            'title' => $data->getTitle()->value(),
            'description' => $data->getDescription()?->value(),
            'deadline' => $data->getDeadline()?->value(),
            'status' => $data->getStatus()->value,
            'priority' => $data->getPriority()->value,
        ]);
        if(!$task){
            throw new Exception('Subtask not created');
        }
        $data->setId($task->id);
    }

    /**
     * @throws Exception
     */
    public function update(Subtask $data): void
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

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function makeEntity(Model $data): Subtask
    {
        $reflection = new ReflectionEntityWithoutConstructor(Subtask::class);

        $reflection->setValueInProperty('taskId', (int)$data->task_id);

        $reflection->setValueInProperty('id', (int)$data->id);

        $reflectionTitle = new ReflectionEntityWithoutConstructor(SubtaskTitle::class);
        $reflectionTitle->setValueInProperty('title', $data->title);
        $reflection->setValueInProperty('title', $reflectionTitle->getEntity());

        $description = $data->description;
        if($description){
            $reflectionDescription = new ReflectionEntityWithoutConstructor(SubtaskDescription::class);
            $reflectionDescription->setValueInProperty('description', $data->description);
            $description = $reflectionDescription->getEntity();
        }
        $reflection->setValueInProperty('description', $description);

        $reflection->setValueInProperty('status', SubtaskStatus::toCase($data->status));

        $reflection->setValueInProperty('priority', SubtaskPriority::toCase($data->priority));

        $reflection->setValueInProperty('deadline', $data->deadline ? new SubtaskDeadline($data->deadline) : null);

        return $reflection->getEntity();
    }
}
