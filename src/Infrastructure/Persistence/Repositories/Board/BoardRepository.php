<?php

namespace Src\Infrastructure\Persistence\Repositories\Board;

use Doctrine\DBAL\LockMode;
use Src\Domain\Board\Board;
use Src\Application\Contracts\Repositories\BoardRepositoryInterface;
use Src\Application\Contracts\Repositories\PaginatedResult;
use Src\Domain\Board\BoardName;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

final readonly class BoardRepository implements BoardRepositoryInterface
{

    public function __construct(
        private EntityManagerInterface $em
    ){}

    public function getAll(): array
    {
        return $this->em->getRepository(Board::class)->findAll();
    }

    public function getWithPaginate(int $page = 1, int $perPage = 15): PaginatedResult
    {
        $qb = $this->em->createQueryBuilder()
            ->select('b')
            ->from(Board::class, 'b')
            ->orderBy('b.id');

        $query = $qb->getQuery()
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $paginator = new Paginator($query);

        return PaginatedResult::make(
            list: iterator_to_array($paginator),
            paginator: [
                'total' => count($paginator),
                'current_page' => $page,
                'limit' => $perPage,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function getById(int $id): Board
    {
        $board = $this->em->getRepository(Board::class)->find($id);
        if (!$board) {
            throw new Exception('The board not found.');
        }
        return $board;
    }

    /**
     * @throws Exception
     */
    public function store(Board $board): void
    {
        $this->em->persist($board);
        $this->em->flush();
        if(!$board->getId()){
            throw new Exception('Board not created');
        }
    }

    public function existsByUserIdAndName(int $userId, BoardName $name): bool
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(b.id)')
            ->from(Board::class, 'b')
            ->where('b.userId = :userId')
            ->andWhere('b.name = :name')
            ->setParameter('userId', $userId)
            ->setParameter('name', $name->value());

        return (int)$qb->getQuery()->getSingleScalarResult() > 0;
    }

    public function getNextIdentity(): int
    {
        return $this->em->wrapInTransaction(function($em) {
            $qb = $em->createQueryBuilder();
            $qb->select('b')
                ->from(Board::class, 'b')
                ->setMaxResults(1)
                ->orderBy('b.id', 'DESC');

            $query = $qb->getQuery();
            $query->setLockMode(LockMode::PESSIMISTIC_WRITE);

            $lastBoard = $query->getOneOrNullResult();

            if ($lastBoard === null) {
                return 1;
            }

            return $lastBoard->getId() + 1;
        });
    }
}
