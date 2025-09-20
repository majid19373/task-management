<?php

namespace Tests\Unit\Application\QueryHandlers\Board;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\Queries\Board\PaginateBoardQuery;
use Src\Application\QueryHandlers\Board\PaginatedListBoardQueryHandler;
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardName;
use Tests\Doubles\Repositories\FakeBoardRepository;

final class PaginatedListBoardQueryHandlerTest extends TestCase
{
    private BoardRepositoryInterface $repository;
    private int $fakeUserId = 1;
    public function setUp(): void
    {
        $this->repository = new FakeBoardRepository();
        for ($i = 0; $i < 20; $i++) {
            $this->createFakeBoard($this->fakeUserId);
        }
        for ($i = 0; $i < 5; $i++) {
            $this->createFakeBoard(2);
        }
    }
    private function createFakeBoard(int $userId): void
    {
        $board = new Board(
            id: $this->repository->getNextIdentity(),
            existsByUserIdAndName: false,
            name: new BoardName(Str::random(10)),
            userId: $userId,
        );
        $this->repository->store($board);
    }

    #[Test]
    public function list_board()
    {
        // Arrange
        $query = new PaginateBoardQuery($this->fakeUserId);
        $sut = new PaginatedListBoardQueryHandler($this->repository);

        // Act
        $result = $sut->handle($query);

        // Assert
        $this->assertCount(10, $result->list);
        $this->assertEquals(1, $result->paginator['current_page']);
        $this->assertEquals(20, $result->paginator['total']);
    }
}
