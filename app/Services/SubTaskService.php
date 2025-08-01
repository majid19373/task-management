<?php

namespace App\Services;

use App\DTO\ServicesResultDTO;
use App\DTO\SubTask\NewSubTaskDTO;
use App\DTO\Task\NewTaskDTO;
use App\DTO\Task\TaskFilterDTO;
use App\Entities\SubTask;
use App\Entities\Task;
use App\Enums\TaskStatusEnum;
use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\Board\BoardRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Rules\Task\CheckPriority;
use App\Rules\Task\CheckStatus;
use App\ValueObjects\Task\TaskDeadline;
use App\ValueObjects\Task\TaskDescription;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskTitle;
use Carbon\Carbon;
use Exception;

final class SubTaskService extends BaseService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly BoardRepositoryInterface $boardRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function create(NewSubTaskDTO $subTaskDTO): void
    {
        if(!$this->boardRepository->isExist($subTaskDTO->boardId)){
            $this->throwException(
                message: 'The board does not exist.',
            );
        }
        if(!$this->taskRepository->isExist($subTaskDTO->taskId)){
            $this->throwException(
                message: 'The task does not exist.',
            );
        }
        $subTask = $this->makeSubTaskEntity($subTaskDTO);
        $this->taskRepository->storeSubTask($subTask);
        $this->taskRepository->findOrFailedById($subTask->getId(), TaskResource::JSON_STRUCTURE);
    }

    /**
     * @throws Exception
     */
    private function makeSubTaskEntity(NewSubTaskDTO $newSubTaskDTO): SubTask
    {
        return new SubTask(
            boardId: (int)$newSubTaskDTO->boardId,
            title: new TaskTitle($newSubTaskDTO->title),
            taskId: (int)$newSubTaskDTO->taskId,
            description: $newSubTaskDTO->description ? new TaskDescription($newSubTaskDTO->description) : null,
            deadline: $newSubTaskDTO->deadline ? new TaskDeadline($newSubTaskDTO->deadline) : null,
        );
    }
}
