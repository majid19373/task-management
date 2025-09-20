<?php

namespace Tests\Doubles\Repositories;

use Exception;
use Illuminate\Support\Str;
use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Application\Repositories\PaginatedResult;
use Src\Application\Repositories\TaskRepositoryInterface;
use Src\Domain\task\task;

class FakeTaskRepository implements TaskRepositoryInterface
{
    /**
     * @var array<task>
     * */
    private array $tasks = [];

    public function list(ListTaskQuery $filters): array
    {
        return array_filter($this->tasks, function (Task $task) use ($filters) {
            if($task->getBoardId() !== $filters->boardId){
                return false;
            }
            if($filters->priority && $filters->priority !== $task->getPriority()){
                return false;
            }
            if($filters->status && $filters->status !== $task->getStatus()){
                return false;
            }
            return true;
        });
    }

    public function listWithPaginate(PaginateTaskQuery $filters): PaginatedResult
    {
        $offset = ($filters->page - 1) * $filters->perPage;
        $listTaskQuery = new ListTaskQuery($filters->boardId, $filters->priority, $filters->status);
        $tasks = $this->list($listTaskQuery);
        $total = count($tasks);
        $items = array_slice($tasks, $offset, $filters->perPage);
        return new PaginatedResult(
            $items,
            [
                'total' => $total,
                'current_page' => $filters->page,
                'limit' => $filters->perPage,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function getById(string $id): Task
    {
        $task = array_find($this->tasks, fn($task) => $task->getId() === $id);
        if (!$task) {
            throw new Exception('The task not found.');
        }
        return $task;
    }

    public function getBySubtaskId(string $id): Task
    {
        //
    }

    public function store(Task $task): void
    {
        $this->tasks[] = $task;
    }

    public function getNextIdentity(): string
    {
        return Str::ulid();
    }
}
