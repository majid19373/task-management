<?php

namespace App\Services;

use App\DTO\Subtask\NewSubtaskDTO;
use App\DTO\Subtask\SubtaskFilterDTO;
use App\Http\Resources\Subtask\SubtaskResource;
use App\Repositories\PaginatedResult;
use App\Repositories\Subtask\SubtaskRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Support\Collection;
use App\ValueObjects\Subtask\{SubtaskDeadline, SubtaskDescription, SubtaskTitle};
use Exception;

final readonly class SubtaskService
{
    public function __construct(
        private TaskRepositoryInterface    $taskRepository,
        private SubtaskRepositoryInterface $subtaskRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(SubtaskFilterDTO $subtaskFilterDTO): PaginatedResult|Collection
    {
        if($subtaskFilterDTO->isPaginated){
            return $this->subtaskRepository->listWithPaginate($subtaskFilterDTO, SubtaskResource::JSON_STRUCTURE);
        }else{
            return $this->subtaskRepository->list($subtaskFilterDTO, SubtaskResource::JSON_STRUCTURE);
        }
    }

    /**
     * @throws Exception
     */
    public function add(NewSubtaskDTO $newSubTaskDTO): void
    {
        $task = $this->taskRepository->getById($newSubTaskDTO->taskId);
        $subTask = $task->addSubtask(
            title: new SubtaskTitle($newSubTaskDTO->title),
            description: $newSubTaskDTO->description ? new SubtaskDescription($newSubTaskDTO->description) : null,
            deadline: $newSubTaskDTO->deadline ? new SubtaskDeadline($newSubTaskDTO->deadline) : null,
        );
        $this->subtaskRepository->store($subTask);
    }
}
