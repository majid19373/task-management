<?php

namespace Tests\Unit\Application\CommandHandlers\Board;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Board\CreateBoardCommandHandler;
use Src\Application\Commands\Board\CreateBoardCommand;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardDescription;
use Src\Domain\Board\BoardName;
use Tests\Doubles\Repositories\FakeBoardRepository;

final class CreateBoardCommandHandlerTest extends TestCase
{
    private const string BOARD_NAME = 'Test Board Name';
    private const string BOARD_DESCRIPTION = 'Test Board Description';
    private const int USER_ID = 1;
    private const string BOARD_ID = 'board_id';

    public static function provideValidNameLengths(): array
    {
        return [
            'MAX' => [50],
            'MIN' => [3],
        ];
    }
    public static function provideInvalidNameLengths(): array
    {
        return [
            'TOO_SHORT' => [0],
            'TOO_LONG' => [51],
        ];
    }

    #[Test]
    public function create_a_board(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $repository->giveAsNextIdentity(self::BOARD_ID);
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: self::USER_ID,
            name: self::BOARD_NAME,
            description: self::BOARD_DESCRIPTION
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll(self::USER_ID);
        $this->assertCount(1, $boards);
        $this->assertEquals(
            new Board(
                id: self::BOARD_ID,
                existsByUserIdAndName: false,
                name: new BoardName(self::BOARD_NAME),
                userId: self::USER_ID,
                description: new BoardDescription(self::BOARD_DESCRIPTION)
            ),
            $boards[0]
        );
        $this->assertEquals(new BoardName($command->name), $boards[0]->getName());
        $this->assertEquals(new BoardDescription($command->description), $boards[0]->getDescription());
    }

    #[Test]
    public function create_a_board_with_optional_description(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: self::USER_ID,
            name: self::BOARD_NAME
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll(self::USER_ID);
        $this->assertCount(1, $boards);
        $this->assertNull($boards[0]->getDescription());
    }

    #[Test]
    public function creating_a_board_when_user_already_has_a_board_with_the_same_name(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: self::USER_ID,
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
    #[DataProvider('provideValidNameLengths')]
    public function creating_a_board_with_valid_name_length(int $length): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: self::USER_ID,
            name: Str::random($length),
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll(self::USER_ID);
        $this->assertCount(1, $boards);
    }

    #[Test]
    #[DataProvider('provideInvalidNameLengths')]
    public function creating_a_board_invalid_name_length(int $length): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: self::USER_ID,
            name: Str::random($length),
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
            userId: self::USER_ID,
            name: self::BOARD_NAME,
            description: $this->maximumDescriptionLength()
        );

        // Act
        $sut->handle($command);

        // Assert
        $boards = $repository->getAll(self::USER_ID);
        $this->assertCount(1, $boards);
    }

    private function maximumDescriptionLength(): string
    {
        return Str::random(200);
    }

    #[Test]
    public function creating_a_board_when_description_length_be_too_long(): void
    {
        // Arrange
        $repository = new FakeBoardRepository();
        $sut = new CreateBoardCommandHandler($repository);
        $command = new CreateBoardCommand(
            userId: self::USER_ID,
            name: self::BOARD_NAME,
            description: $this->tooLongDescriptionLength()
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    private function tooLongDescriptionLength(): string
    {
        return Str::random(201);
    }
}
