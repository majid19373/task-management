<?php

namespace Tests\Unit\Application\CommandHandlers\Board;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Board\CreateBoardCommandHandler;
use Src\Application\Commands\Board\CreateBoardCommand;
use Src\Domain\Board\BoardName;
use Tests\Doubles\Repositories\FakeBoardRepository;

final class CreateBoardCommandHandlerTest extends TestCase
{
    private const string BOARD_NAME = 'Test Board Name';
    private const string BOARD_DESCRIPTION = 'Test Board Description';
    #[Test]
    public function create_a_board(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: self::BOARD_NAME,
            description: self::BOARD_DESCRIPTION
        );

        // Act
        $sut->handle($command);

        // Arrange
        $boards = $repository->getAll(1);
        $this->assertCount(1, $boards);
        $this->assertEquals(new BoardName($command->name), $boards[0]->getName());
    }

    #[Test]
    public function create_a_board_with_optional_description(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: self::BOARD_NAME
        );

        // Act
        $sut->handle($command);

        // Arrange
        $boards = $repository->getAll(1);
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_when_user_already_has_a_board_with_the_same_name(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: self::BOARD_NAME,
            description: self::BOARD_DESCRIPTION
        );
        $sut->handle($command);

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_board_with_maximum_name_length(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: Str::random(50),
            description: self::BOARD_DESCRIPTION
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll(1);
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_with_minimum_name_length(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: Str::random(3),
            description: self::BOARD_DESCRIPTION
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll(1);
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_when_name_length_be_too_short(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: '',
            description: self::BOARD_DESCRIPTION
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_board_when_name_length_be_too_long(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: Str::random(51),
            description: self::BOARD_DESCRIPTION
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_board_with_maximum_description_length(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: self::BOARD_NAME,
            description: Str::random(200)
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll(1);
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_when_description_length_be_too_long(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: self::BOARD_NAME,
            description: Str::random(201)
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }
}
