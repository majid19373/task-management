<?php

namespace Tests\Unit\Application\CommandHandlers;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Board\CreateBoardCommandHandler;
use Src\Application\Commands\Board\CreateBoardCommand;
use Src\Domain\Board\BoardName;
use Tests\Doubles\Repositories\FakeBoardRepository;

class CreateBoardCommandHandlerTest extends TestCase
{
    #[Test]
    public function create_a_board()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: 'Test Board',
            description: 'Test Description'
        );

        // Act
        $sut->handle($command);

        // Arrange
        $boards = $repository->getAll();
        $this->assertCount(1, $boards);
        $this->assertEquals(new BoardName($command->name), $boards[0]->getName());
    }

    #[Test]
    public function create_a_board_with_optional_description()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: 'Test Board'
        );

        // Act
        $sut->handle($command);

        // Arrange
        $boards = $repository->getAll();
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_when_user_already_has_a_board_with_the_same_name()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: 'Test Board',
            description: 'Test Description'
        );
        $sut->handle($command);

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_board_with_maximum_name_length()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: Str::random(50),
            description: 'Test Description'
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll();
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_with_minimum_name_length()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: Str::random(3),
            description: 'Test Description'
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll();
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_when_name_length_be_too_short()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: '',
            description: 'Test Description'
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_board_when_name_length_be_too_long()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: Str::random(51),
            description: 'Test Description'
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_board_with_maximum_description_length()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: 'Test Board',
            description: Str::random(200)
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll();
        $this->assertCount(1, $boards);
    }

    #[Test]
    public function creating_a_board_when_description_length_be_too_long()
    {
        // Assert
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: 1,
            name: 'Test Board',
            description: Str::random(201)
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }
}
