<?php

namespace App\Repositories\Board;

use App\Entities\Board;
use App\Repositories\PaginatedResult;
use App\ValueObjects\Board\{BoardName};
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
            ->setFirstResult(($page - 1) * $perPage) // OFFSET
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

    public function getById(int $id): Board
    {
        return $this->em->getRepository(Board::class)->find($id);
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
}
