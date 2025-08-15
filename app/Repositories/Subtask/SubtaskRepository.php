<?php

namespace App\Repositories\Subtask;

use App\DTO\Subtask\SubtaskFilter;
use App\Entities\Subtask;
use App\Models\Subtask as Model;
use App\Repositories\PaginatedResult;
use App\ValueObjects\Subtask\{SubtaskDescription, SubtaskStatus, SubtaskTitle};
use Exception;
use Illuminate\Support\Collection;

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
     * @throws Exception
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
            'title' => $data->getTitle()->value(),
            'description' => $data->getDescription()?->value(),
            'status' => $data->getStatus()->value,
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
            'status' => $data->getStatus()->value,
        ]);
        if(!$task){
            throw new Exception('Task not updated');
        }
    }

    /**
     * @throws Exception
     */
    private function makeEntity(Model $data): Subtask
    {
        return Subtask::reconstitute(
            id: (int)$data->id,
            taskId: (int)$data->task_id,
            title: SubtaskTitle::reconstitute($data->title ?? ''),
            status: SubtaskStatus::from($data->status),
            description: $data->description ? SubtaskDescription::reconstitute($data->description) : null
        );
    }
}
