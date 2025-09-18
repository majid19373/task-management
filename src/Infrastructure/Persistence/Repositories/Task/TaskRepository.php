<?php

namespace Src\Infrastructure\Persistence\Repositories\Task;

use Doctrine\DBAL\LockMode;
use Illuminate\Support\Str;
use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Queries\Task\PaginateTaskQuery;
use Src\Domain\Subtask\Subtask;
use Src\Domain\Task\Task;
use Src\Application\Repositories\PaginatedResult;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Src\Application\Repositories\TaskRepositoryInterface;

final readonly class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ){
    }

    public function list(ListTaskQuery $filters): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('t')
            ->from(Task::class, 't')
            ->where('t.boardId = :boardId')
            ->setParameter('boardId', $filters->boardId);

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
            ->select('t')
            ->from(Task::class, 't')
            ->where('t.boardId = :boardId')
            ->setParameter('boardId', $filters->boardId);

        if ($filters->status !== null) {
            $qb->andWhere('t.status = :status')
                ->setParameter('status', $filters->status);
        }

        if ($filters->priority !== null) {
            $qb->andWhere('t.priority = :priority')
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
    public function getById(string $id): Task
    {
        return $this->em->getRepository(Task::class)->find($id);
    }

    /**
     * @throws Exception
     */
    public function getBySubtaskId(string $id): Task
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

    public function getNextIdentity(): string
    {
        return Str::ulid();
    }
}
