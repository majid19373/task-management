<?php

namespace App\Repositories\Task;

use App\DTO\Task\TaskFilter;
use App\Entities\Task;
use App\Repositories\PaginatedResult;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Illuminate\Database\Eloquent\Builder;

final readonly class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ){
    }

    public function list(TaskFilter $filters): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('b')
            ->from(Task::class, 'b')
            ->orderBy('b.id');

        if ($filters->status !== null) {
            $qb->andWhere('b.status = :status')
                ->setParameter('status', $filters->status);
        }

        if ($filters->priority !== null) {
            $qb->andWhere('b.priority = :priority')
                ->setParameter('priority', $filters->priority);
        }

        return $qb->getQuery()->getResult();

    }

    public function listWithPaginate(
        TaskFilter $filters,
    ): PaginatedResult
    {
        $qb = $this->em->createQueryBuilder()
            ->select('b')
            ->from(Task::class, 'b')
            ->orderBy('b.id');

        if ($filters->status !== null) {
            $qb->andWhere('b.status = :status')
                ->setParameter('status', $filters->status);
        }

        if ($filters->priority !== null) {
            $qb->andWhere('b.priority = :priority')
                ->setParameter('priority', $filters->priority);
        }

        $query = $qb->getQuery()
            ->setFirstResult(($filters->page - 1) * $filters->perPage)
            ->setMaxResults($filters->perPage);

        $paginator = new Paginator($query);

        return PaginatedResult::make(
            list: iterator_to_array($paginator),
            paginator: [
                'total' => count($paginator),
                'current_page' => $filters->page,
                'limit' => $filters->perPage,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function getById(int $id): Task
    {
        return $this->em->getRepository(Task::class)->find($id);
    }

    /**
     * @throws Exception
     */
    public function getBySubtaskId(int $id): Task
    {
        $task = $this->model->query()
            ->with(['subtasks'])
            ->where('subtask_id', '=', $id)
            ->first();
        if(!$task) {
            throw new Exception('Subtask not found');
        }
        return $this->makeEntityForTask($task);
    }

    /**
     * @throws Exception
     */
    public function store(Task $task): void
    {
        $this->em->persist($task);
        $this->em->flush();
        if(!$task->getId()){
            throw new Exception('Task not created');
        }
    }

    /**
     * @throws Exception
     */
    public function update(Task $task): void
    {
        if (!$task->getId()) {
            throw new Exception('Task must already exist before update.');
        }
        $this->em->persist($task);
        $this->em->flush();
    }

    private function applyTaskFilters(Builder $query, TaskFilter $filters): Builder
    {
        return $query
            ->when($filters->status, fn($q) => $q->where('status', $filters->status))
            ->when($filters->priority, fn($q) => $q->where('priority', $filters->priority));
    }
}
