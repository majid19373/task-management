<?php

namespace App\Services;

use App\DTO\Subtask\NewSubtaskDTO;
use App\DTO\Subtask\SubtaskFilterDTO;
use App\Entities\Subtask;
use App\Http\Resources\Subtask\SubtaskResource;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;
use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskTitle};
use App\Repositories\Task\{TaskRepositoryInterface};
use Exception;

final readonly class SubtaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(SubtaskFilterDTO $subtaskFilterDTO): PaginatedResult|Collection
    {
        if($subtaskFilterDTO->isPaginated){
            return $this->taskRepository->subtaskListWithPaginate($subtaskFilterDTO, SubtaskResource::JSON_STRUCTURE);
        }else{
            return $this->taskRepository->subtaskList($subtaskFilterDTO, SubtaskResource::JSON_STRUCTURE);
        }
    }

    /**
     * @throws Exception
     */
    public function add(NewSubtaskDTO $subTaskDTO): void
    {
        if(!$this->taskRepository->isExist($subTaskDTO->taskId)){
            throw new Exception(
                message: 'The task does not exist.',
            );
        }
        $subTask = $this->makeEntityForAdd($subTaskDTO);
        $this->taskRepository->storeSubTask($subTask);
    }

    /**
     * @throws Exception
     */
    private function makeEntityForAdd(NewSubtaskDTO $newSubTaskDTO): Subtask
    {
        return new Subtask(
            taskId: (int)$newSubTaskDTO->taskId,
            title: new TaskTitle($newSubTaskDTO->title),
            description: $newSubTaskDTO->description ? new TaskDescription($newSubTaskDTO->description) : null,
            deadline: $newSubTaskDTO->deadline ? new TaskDeadline($newSubTaskDTO->deadline) : null,
        );
    }
}
