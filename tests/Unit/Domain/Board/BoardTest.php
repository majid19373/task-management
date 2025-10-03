<?php

namespace Tests\Unit\Domain\Board;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Board\Board;
use Src\Domain\Board\BoardDescription;
use Src\Domain\Board\BoardName;
use DomainException;
use Src\Domain\Task\TaskDeadline;
use Src\Domain\Task\TaskDescription;
use Src\Domain\Task\TaskTitle;

final class BoardTest extends TestCase
{
    private const string BOARD_NAME = 'Test Board Name';
    private const string TASK_TITLE = 'Test Task title';
    private const string BOARD_DESCRIPTION = 'Test Board Description';
    private const string TASK_DESCRIPTION = 'Test Task Description';
    private const string BOARD_ID = 'board_id';
    private const string TASK_ID = 'board_id';
    private const bool EXISTS_BY_USER_AND_NAME = true;
    private const bool DOESNT_EXISTS_BY_USER_AND_NAME = false;
    private const int USER_ID = 1;
    private function prepareBoardForTask(): Board
    {
        return new Board(
            id: self::BOARD_ID,
            existsByUserIdAndName: self::DOESNT_EXISTS_BY_USER_AND_NAME,
            name: new BoardName(self::BOARD_NAME),
            userId: self::USER_ID,
        );
    }

    private function createTaskDeadline(): string
    {
        return now()->addMinute()->format('Y-m-d H:i:s');
    }

    #[Test]
    public function create_a_board()
    {
        // Arrange
        $name = new BoardName(self::BOARD_NAME);
        $description = new BoardDescription(self::BOARD_DESCRIPTION);

        // Act
        $board = new Board(
            id: self::BOARD_ID,
            existsByUserIdAndName: self::DOESNT_EXISTS_BY_USER_AND_NAME,
            name: $name,
            userId: self::USER_ID,
            description: $description
        );

        // Assert
        $this->assertEquals($name, $board->getName());
        $this->assertEquals($description, $board->getDescription());
        $this->assertEquals(self::BOARD_ID, $board->getId());
        $this->assertEquals(self::USER_ID, $board->getUserId());
    }

    #[Test]
    public function create_a_board_with_optional_description()
    {
        // Arrange
        $name = new BoardName(self::BOARD_NAME);

        // Act
        $board = new Board(
            id: self::BOARD_ID,
            existsByUserIdAndName: self::DOESNT_EXISTS_BY_USER_AND_NAME,
            name: $name,
            userId: self::USER_ID,
            description: null
        );

        // Assert
        $this->assertNull($board->getDescription());
    }

    #[Test]
    public function create_a_board_with_same_name_and_user()
    {
        // Arrange
        $name = new BoardName(self::BOARD_NAME);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Board name already exists for this user.');

        // Act
        new Board(
            id: self::BOARD_ID,
            existsByUserIdAndName: self::EXISTS_BY_USER_AND_NAME,
            name: $name,
            userId: self::USER_ID,
        );
    }

    #[Test]
    public function create_a_task_by_board()
    {
        // Arrange
        $board = $this->prepareBoardForTask();
        $title = new TaskTitle(self::TASK_TITLE);
        $description = new TaskDescription(self::TASK_DESCRIPTION);
        $deadline = new TaskDeadline($this->createTaskDeadline());

        // Act
        $task = $board->addTask(
            taskId: self::TASK_ID,
            title: $title,
            description: $description,
            deadline: $deadline,
        );

        // Assert
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($description, $task->getDescription());
        $this->assertEquals($deadline, $task->getDeadline());
        $this->assertEquals($board->getId(), $task->getBoardId());
    }

    #[Test]
    public function create_a_task_with_optional_description_by_board()
    {
        // Arrange
        $board = $this->prepareBoardForTask();
        $title = new TaskTitle(self::TASK_TITLE);
        $description = new TaskDescription(self::TASK_DESCRIPTION);
        $deadline = new TaskDeadline($this->createTaskDeadline());

        // Act
        $task = $board->addTask(
            taskId: self::TASK_ID,
            title: $title,
            description: null,
            deadline: $deadline,
        );

        // Assert
        $this->assertEquals($title, $task->getTitle());
        $this->assertNull($task->getDescription());
        $this->assertEquals($deadline, $task->getDeadline());
        $this->assertEquals($board->getId(), $task->getBoardId());
    }

    #[Test]
    public function create_a_task_with_optional_deadline_by_board()
    {
        // Arrange
        $board = $this->prepareBoardForTask();
        $title = new TaskTitle(self::TASK_TITLE);
        $description = new TaskDescription(self::TASK_DESCRIPTION);

        // Act
        $task = $board->addTask(
            taskId: self::TASK_ID,
            title: $title,
            description: $description,
            deadline: null,
        );

        // Assert
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($description, $task->getDescription());
        $this->assertNull($task->getDeadline());
        $this->assertEquals($board->getId(), $task->getBoardId());
    }
}
