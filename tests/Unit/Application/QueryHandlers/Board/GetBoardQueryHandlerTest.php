<?php

namespace Tests\Unit\Application\QueryHandlers\Board;

use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\Queries\Board\GetBoardQuery;
use Src\Application\QueryHandlers\Board\GetBoardQueryHandler;
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardName;
use Tests\Doubles\Repositories\FakeBoardRepository;

final class GetBoardQueryHandlerTest extends TestCase
{
    private Board $board;
    private BoardRepositoryInterface $repository;
    public function setUp(): void
    {
        $this->repository = new FakeBoardRepository();
        $this->board = new Board(
            id: $this->repository->getNextIdentity(),
            existsByUserIdAndName: false,
            name: new BoardName('Test Board'),
            userId: 1,
        );
        $this->repository->store($this->board);
    }

    #[Test]
    public function get_the_board(): void
    {
        // Arrange
        $query = new GetBoardQuery($this->board->getId());
        $sut = new GetBoardQueryHandler($this->repository);

        // Act
        $result = $sut->handle($query);

        // Assert
        $this->assertEquals($this->board, $result);
    }

    #[Test]
    public function get_the_board_when_id_is_not_exist(): void
    {
        // Arrange
        $query = new GetBoardQuery('wrong_id');
        $sut = new GetBoardQueryHandler($this->repository);

        // Expect
        $this->expectException(Exception::class);

        // Act
        $sut->handle($query);
    }
}
