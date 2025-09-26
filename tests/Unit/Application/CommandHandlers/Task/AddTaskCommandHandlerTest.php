<?php

namespace Tests\Unit\Application\CommandHandlers\Task;

use DomainException;
use Exception;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Task\AddTaskCommandHandler;
use Src\Application\Commands\Task\AddTaskCommand;
use Src\Application\Queries\Task\ListTaskQuery;
use Src\Application\Repositories\BoardRepositoryInterface;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardName;
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
    private Board $board;
    private BoardRepositoryInterface $boardRepository;
    public function setUp(): void
    {
        $this->boardRepository = new FakeBoardRepository();
        $this->board = new Board(
            id: $this->boardRepository->getNextIdentity(),
            existsByUserIdAndName: false,
            name: new BoardName(self::BOARD_NAME),
            userId: 1,
        );
        $this->boardRepository->store($this->board);
    }

    #[Test]
    public function create_a_task(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: self::TASK_TITLE,
            description: self::TASK_DESCRIPTION
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->list(new ListTaskQuery($this->board->getId()));
        $this->assertCount(1, $tasks);
        $this->assertEquals(new TaskTitle($command->title), $tasks[0]->getTitle());
        $this->assertEquals(TaskStatus::NOT_STARTED, $tasks[0]->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $tasks[0]->getPriority());
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
        $tasks = $repository->list(new ListTaskQuery($this->board->getId()));
        $this->assertCount(1, $tasks);
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
            deadline: '2020-01-01',
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
    public function creating_a_task_with_maximum_title_length(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: Str::random(100),
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->list(new ListTaskQuery($this->board->getId()));
        $this->assertCount(1, $tasks);
    }

    #[Test]
    public function creating_a_task_with_minimum_title_length(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: Str::random(5),
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->list(new ListTaskQuery($this->board->getId()));
        $this->assertCount(1, $tasks);
    }

    #[Test]
    public function creating_a_task_when_title_length_be_too_short(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: '',
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }

    #[Test]
    public function creating_a_task_when_title_length_be_too_long(): void
    {
        // Arrange
        $repository = new FakeTaskRepository();
        $sut = new AddTaskCommandHandler($repository, $this->boardRepository);
        $command = new AddTaskCommand(
            boardId: $this->board->getId(),
            title: Str::random(101),
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
            description: Str::random(500)
        );

        // Act
        $sut->handle($command);

        // Assert
        $tasks = $repository->list(new ListTaskQuery($this->board->getId()));
        $this->assertCount(1, $tasks);
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
            description: Str::random(501)
        );

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }
}
