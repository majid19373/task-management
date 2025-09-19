<?php

namespace Src\Infrastructure\Persistence\Repositories\Board;

use Illuminate\Support\Str;
use Src\Domain\Board\Board;
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Application\Repositories\PaginatedResult;
use Src\Domain\Board\BoardName;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

final readonly class BoardRepository implements BoardRepositoryInterface
{

    public function __construct(
        private EntityManagerInterface $em
    ){}

    public function getAll(int $userId): array
    {
        return $this->em->createQueryBuilder()
            ->select('b')
            ->from(Board::class, 'b')
            ->where('b.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function getWithPaginate(int $userId, int $page = 1, int $perPage = 15): PaginatedResult
    {
        $qb = $this->em->createQueryBuilder()
            ->select('b')
            ->from(Board::class, 'b')
            ->where('b.userId = :userId')
            ->setParameter('userId', $userId);

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
    public function getById(string $id): Board
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

    public function getNextIdentity(): string
    {
        return Str::ulid();
    }
}
