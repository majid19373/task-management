<?php

namespace Tests\Unit\Application\CommandHandlers\Task;

use DomainException;
use Exception;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Task\AddTaskCommandHandler;
use Src\Application\Commands\Task\AddTaskCommand;
use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardName;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskDescription;
use Src\Domain\Task\TaskPriority;
use Src\Domain\Task\TaskStatus;
use Src\Domain\Task\TaskTitle;
use Tests\Doubles\Repositories\FakeBoardRepository;
use Tests\Doubles\Repositories\FakeTaskRepository;

final class AddTaskCommandHandlerTest extends TestCase
{
    private const string BOARD_NAME = 'Test BOARD NAME';
    private const string TASK_TITLE = 'Test Task Title';
    private const string TASK_DESCRIPTION = 'Test Task Description';
    private const string TASK_ID = 'task_id';
    private const int USER_ID = 1;
    private Board $board;
    private BoardRepositoryInterface $boardRepository;
    public static function provideValidTitleLengths(): array
    {
        return [
            'MAX' => [100],
            'MIN' => [5],
        ];
    }
    public static function provideInvalidTitleLengths(): array
    {
        return [
            'TOO_SHORT' => [0],
            'TOO_LONG' => [101],
        ];
    }

    public function setUp(): void
    {
        $this->boardRepository = new FakeBoardRepository();
        $this->board = new Board(
            id: $this->boardRepository->getNextIdentity(),
            existsByUserIdAndName: false,
            name: new BoardName(self::BOARD_NAME),
            userId: self::USER_ID,
        );
        $this->boardRepository->store($this->board);
    }

    #[Test]
    public function create_a_task(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $repository->giveAsNextIdentity(self::TASK_ID);
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: self::TASK_TITLE,
            description: self::TASK_DESCRIPTION
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->getAll();
        $this->assertCount(1, $tasks);
        $this->assertEquals(
            new Task(
                id: self::TASK_ID,
                boardId: $this->board->getId(),
                title: new TaskTitle(self::TASK_TITLE),
                description: new TaskDescription(self::TASK_DESCRIPTION)
            ),
            $tasks[0]
        );
        $this->assertEquals(new TaskTitle($command->title), $tasks[0]->getTitle());
        $this->assertEquals(TaskStatus::NOT_STARTED, $tasks[0]->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $tasks[0]->getPriority());
        $this->assertEquals(new TaskDescription(self::TASK_DESCRIPTION), $tasks[0]->getDescription());
    }

    #[Test]
    public function create_a_task_with_optional_description(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: self::TASK_TITLE,
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->getAll();
        $this->assertCount(1, $tasks);
        $this->assertNull($tasks[0]->getDescription());
    }

    #[Test]
    public function create_a_task_with_past_deadline(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: self::TASK_TITLE,
            deadline: '2020-01-01 00:00:00',
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function create_a_task_with_not_exist_board(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: 'not_exist',
            title: self::TASK_TITLE,
        );

        // Expect
        $this->expectException(Exception::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    #[DataProvider('provideValidTitleLengths')]
    public function creating_a_task_with_valid_title_length(int $length): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: Str::random($length),
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->getAll();
        $this->assertCount(1, $tasks);
    }

    #[Test]
    #[DataProvider('provideInvalidTitleLengths')]
    public function creating_a_task_with_invalid_title_length(int $length): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: Str::random($length),
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_task_with_maximum_description_length(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: self::TASK_TITLE,
            description: $this->maximumDescriptionLength(),
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->getAll();
        $this->assertCount(1, $tasks);
    }

    private function maximumDescriptionLength(): string
    {
        return Str::random(500);
    }

    #[Test]
    public function creating_a_task_when_description_length_be_too_long(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: self::TASK_TITLE,
            description: $this->tooLongDescriptionLength(),
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    private function tooLongDescriptionLength(): string
    {
        return Str::random(501);
    }
}
