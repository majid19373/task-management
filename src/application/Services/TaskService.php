<?php

namespace Src\application\Services;

use Src\application\DTO\Task\NewTask;
use Src\application\DTO\Task\TaskFilter;
use Src\domain\Entities\Task\Task;
use Src\domain\Entities\Task\ValueObjects\TaskDeadline;
use Src\domain\Entities\Task\ValueObjects\TaskDescription;
use Src\domain\Entities\Task\ValueObjects\TaskPriority;
use Src\domain\Entities\Task\ValueObjects\TaskStatus;
use Src\persistence\Repositories\Board\BoardRepositoryInterface;
use Src\persistence\Repositories\Task\TaskRepositoryInterface;
use DateTimeImmutable;
use Src\domain\Entities\Task\ValueObjects\{TaskTitle};
use Exception;
use Src\persistence\Repositories\PaginatedResult;

final readonly class TaskService
{
    public function __construct(
        private TaskRepositoryInterface  $taskRepository,
        private BoardRepositoryInterface $boardRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function list(TaskFilter $taskFilter): PaginatedResult|array
    {
        if($taskFilter->status){
            TaskStatus::validate($taskFilter->status);
        }
        if($taskFilter->priority){
            TaskPriority::validate($taskFilter->priority);
        }
        if($taskFilter->isPaginated){
            return $this->taskRepository->listWithPaginate($taskFilter);
        }else{
            return $this->taskRepository->list($taskFilter);
        }
    }

    /**
     * @throws Exception
     */
    public function add(NewTask $newTask): void
    {
        $board = $this->boardRepository->getById($newTask->boardId);
        $task = $board->addTask(
            title: new TaskTitle($newTask->title),
            description: $newTask->description ? new TaskDescription($newTask->description) : null,
            deadline: $newTask->deadline ? new TaskDeadline($newTask->deadline, new DateTimeImmutable()) : null,
        );
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function findById(int $taskId): Task
    {
        return $this->taskRepository->getById($taskId);
    }

    /**
     * @throws Exception
     */
    public function start(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->start();
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function complete(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->complete();
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function reopen(int $taskId): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->reopen();
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function prioritize(int $taskId, string $priority): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->prioritize(TaskPriority::validate($priority));
        $this->taskRepository->store($task);
    }

    /**
     * @throws Exception
     */
    public function changeDeadline(int $taskId, string $deadline): void
    {
        $task = $this->taskRepository->getById($taskId);
        $task->changeDeadline(new TaskDeadline($deadline, new DateTimeImmutable()));
        $this->taskRepository->store($task);
    }
}
