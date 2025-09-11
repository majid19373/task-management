<?php

namespace Src\Infrastructure\Persistence\Repositories\Task;

use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Domain\Task\Task;
use Src\Application\Contracts\Repositories\PaginatedResult;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Src\Application\Contracts\Repositories\TaskRepositoryInterface;

final readonly class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ){
    }

    public function list(ListTaskQuery $filters): array
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
        PaginateTaskQuery $filters,
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
        $qb = $this->em->createQueryBuilder();

        $qb->select('t', 's')
        ->from(Task::class, 't')
            ->leftJoin('t.subtasks', 's')
            ->where('s.id = :subtaskId')
            ->setParameter('subtaskId', $id)
            ->setMaxResults(1);

        $subtask = $qb->getQuery()->getOneOrNullResult();
        if(!$subtask) {
            throw new Exception('Subtask not found');
        }
        return $subtask;
    }

    public function store(Task $task): void
    {
        $this->em->persist($task);
        $this->em->flush();
    }
}
