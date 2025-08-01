<?php

namespace App\Services;

use App\DTO\Task\NewTaskDTO;
use App\DTO\Task\TaskFilterDTO;
use App\Entities\Task;
use App\Enums\TaskStatusEnum;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\Board\BoardRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Rules\Task\CheckFutureDeadline;
use App\Rules\Task\CheckPriority;
use App\Rules\Task\CheckStatus;
use App\ValueObjects\Task\TaskDeadline;
use App\ValueObjects\Task\TaskDescription;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskTitle;
use Carbon\Carbon;
use Exception;
use App\Repositories\PaginatedResult;
use Illuminate\Support\Collection;

final readonly class TaskService
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
        private BoardRepositoryInterface $boardRepository,
        private CheckStatus              $checkStatus,
        private CheckPriority            $checkPriority,
        private CheckFutureDeadline      $checkFutureDeadline,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(TaskFilterDTO $taskFilterDTO): PaginatedResult|Collection
    {
        $this->checkStatus->validate($taskFilterDTO->status);
        $this->checkPriority->validate($taskFilterDTO->priority);
        if($taskFilterDTO->isPaginated){
            return $this->taskRepository->getWithPaginate($taskFilterDTO, TaskResource::JSON_STRUCTURE);
        }else{
            return $this->taskRepository->all($taskFilterDTO, TaskResource::JSON_STRUCTURE);
        }
    }

    /**
     * @throws Exception
     */
    public function create(NewTaskDTO $taskDTO): void
    {
        if(!$this->boardRepository->isExist($taskDTO->boardId)){
            throw new Exception(
                message: 'The board does not exist.',
            );
        }
        $this->checkFutureDeadline->validate($taskDTO->deadline);
        $task = $this->makeTaskEntity($taskDTO);
        $this->taskRepository->storeTask($task);
        $this->taskRepository->findOrFailedById($task->getId(), TaskResource::JSON_STRUCTURE);
    }

    /**
     * @throws Exception
     */
    public function findById(int $taskId): Task
    {
        return $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): void
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus()->value() !== TaskStatusEnum::NOT_STARTED->value){
            throw new Exception(
                message: 'The task must not have started.',
            );
        }
        $task->setStatus(new TaskStatus(TaskStatusEnum::IN_PROGRESS->value));
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function completed(int $taskId): void
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus()->value() !== TaskStatusEnum::IN_PROGRESS->value){
            throw new Exception(
                message: 'The task must not have completed.',
            );
        }
        $task->setStatus(new TaskStatus(TaskStatusEnum::COMPLETED->value));
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): void
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus()->value() !== TaskStatusEnum::COMPLETED->value){
            throw new Exception(
                message: 'The task cannot reopened.',
            );
        }
        $task->setStatus(new TaskStatus(TaskStatusEnum::NOT_STARTED->value));
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function changePriority(int $taskId, string $priority): void
    {
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus()->value() === TaskStatusEnum::COMPLETED->value){
            throw new Exception(
                message: 'The task cannot change the priority.',
            );
        }
        $task->setPriority(new TaskPriority($priority));
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    public function changeDeadline(int $taskId, string $deadline): void
    {
        $this->checkFutureDeadline->validate($deadline);
        $task = $this->taskRepository->findOrFailedById($taskId, TaskResource::JSON_STRUCTURE);
        if($task->getStatus()->value() === TaskStatusEnum::COMPLETED->value){
            throw new Exception(
                message: 'The task cannot change the deadline.',
            );
        }
        $task->setDeadline(new TaskDeadline($deadline));
        $this->taskRepository->update($task);
    }

    /**
     * @throws Exception
     */
    private function makeTaskEntity(NewTaskDTO $newTaskDTO): Task
    {
        return new Task(
            boardId: (int)$newTaskDTO->boardId,
            title: new TaskTitle($newTaskDTO->title),
            description: $newTaskDTO->description ? new TaskDescription($newTaskDTO->description) : null,
            deadline: $newTaskDTO->deadline ? new TaskDeadline($newTaskDTO->deadline) : null,
        );
    }
}
