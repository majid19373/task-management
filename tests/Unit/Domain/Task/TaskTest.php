<?php

namespace Tests\Unit\Domain\Task;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use DomainException;
use Src\Domain\Subtask\Subtask;
use Src\Domain\Subtask\SubtaskDescription;
use Src\Domain\Subtask\SubtaskStatus;
use Src\Domain\Subtask\SubtaskTitle;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskDeadline;
use Src\Domain\Task\TaskDescription;
use Src\Domain\Task\TaskPriority;
use Src\Domain\Task\TaskStatus;
use Src\Domain\Task\TaskTitle;

final class TaskTest extends TestCase
{
    private const string TASK_TITLE = 'Test Task title';
    private const string SUBTASK_TITLE = 'Test Subtask title';
    private const string TASK_DESCRIPTION = 'Test Task Description';
    private const string SUBTASK_DESCRIPTION = 'Test Subtask Description';
    private const string BOARD_ID = 'board_id';
    private const string TASK_ID = 'task_id';
    private const string PASSED_DATE = '2020-01-01 00:00:00';
    private function futureDeadline(): string
    {
        return now()->addMinute()->format('Y-m-d H:i:s');
    }
    private function prepareTask(): Task
    {
        return new Task(
            id: self::TASK_ID,
            boardId: self::BOARD_ID,
            title: new TaskTitle(self::TASK_TITLE),
        );
    }

    #[Test]
    public function create_a_task()
    {
        // Arrange
        $title = new TaskTitle(self::TASK_TITLE);
        $description = new TaskDescription(self::TASK_DESCRIPTION);
        $deadline = new TaskDeadline($this->futureDeadline());

        // Act
        $task = new Task(
            id: self::TASK_ID,
            boardId: self::BOARD_ID,
            title: $title,
            description: $description,
            deadline: $deadline,
        );

        // Assert
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($description, $task->getDescription());
        $this->assertEquals(self::TASK_ID, $task->getId());
        $this->assertEquals(self::BOARD_ID, $task->getBoardId());
        $this->assertEquals($deadline, $task->getDeadline());
        $this->assertEquals(TaskStatus::NOT_STARTED, $task->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $task->getPriority());
    }

    #[Test]
    public function create_a_task_with_optional_description()
    {
        // Arrange
        $title = new TaskTitle(self::TASK_TITLE);
        $deadline = new TaskDeadline($this->futureDeadline());

        // Act
        $task = new Task(
            id: self::TASK_ID,
            boardId: self::BOARD_ID,
            title: $title,
            deadline: $deadline,
        );

        // Assert
        $this->assertNull($task->getDescription());
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals(self::TASK_ID, $task->getId());
        $this->assertEquals(self::BOARD_ID, $task->getBoardId());
        $this->assertEquals($deadline, $task->getDeadline());
        $this->assertEquals(TaskStatus::NOT_STARTED, $task->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $task->getPriority());
    }

    #[Test]
    public function create_a_task_with_optional_deadline()
    {
        // Arrange
        $title = new TaskTitle(self::TASK_TITLE);
        $description = new TaskDescription(self::TASK_DESCRIPTION);

        // Act
        $task = new Task(
            id: self::TASK_ID,
            boardId: self::BOARD_ID,
            title: $title,
            description: $description,
        );

        // Assert
        $this->assertNull($task->getDeadline());
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($description, $task->getDescription());
        $this->assertEquals(self::TASK_ID, $task->getId());
        $this->assertEquals(self::BOARD_ID, $task->getBoardId());
        $this->assertEquals(TaskStatus::NOT_STARTED, $task->getStatus());
        $this->assertEquals(TaskPriority::MEDIUM, $task->getPriority());
    }

    #[Test]
    public function create_a_task_with_passed_deadline()
    {
        // Arrange
        $title = new TaskTitle(self::TASK_TITLE);
        $deadline = new TaskDeadline(self::PASSED_DATE);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The deadline date must be greater than the current date.');

        // Act
        new Task(
            id: self::TASK_ID,
            boardId: self::BOARD_ID,
            title: $title,
            deadline: $deadline,
        );
    }

    #[Test]
    public function create_a_subtask_by_task()
    {
        // Arrange
        $title = new SubtaskTitle(self::TASK_TITLE);
        $description = new SubtaskDescription(self::TASK_DESCRIPTION);
        $sut = $this->prepareTask();

        // Act
        $sut->addSubtask(
            title: $title,
            description: $description,
        );

        // Assert
        $subtasks = $sut->getSubtasks();
        $this->assertCount(1, $subtasks);
        $this->assertEquals($title, $subtasks[0]->getTitle());
        $this->assertEquals($description, $subtasks[0]->getDescription());
    }

    #[Test]
    public function create_a_subtask_by_task_with_task_status_is_completed()
    {
        // Arrange
        $title = new SubtaskTitle(self::SUBTASK_TITLE);
        $description = new SubtaskDescription(self::SUBTASK_DESCRIPTION);
        $sut = $this->prepareTask();
        $sut->start();
        $sut->complete();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Can not add a subtask to a completed task.');

        // Act
        $sut->addSubtask(
            title: $title,
            description: $description,
        );
    }

    #[Test]
    public function create_a_subtask_by_task_with_optional_subtask_description()
    {
        // Arrange
        $title = new SubtaskTitle(self::SUBTASK_TITLE);
        $sut = $this->prepareTask();

        // Act
        $sut->addSubtask(
            title: $title,
            description: null,
        );

        // Assert
        $subtasks = $sut->getSubtasks();
        $this->assertCount(1, $subtasks);
        $this->assertEquals($title, $subtasks[0]->getTitle());
        $this->assertNull($subtasks[0]->getDescription());
    }

    #[Test]
    public function start_a_task()
    {
        // Arrange
        $sut = $this->prepareTask();

        // Act
        $sut->start();

        // Assert
        $this->assertEquals(TaskStatus::IN_PROGRESS, $sut->getStatus());
    }

    #[Test]
    public function start_a_task_when_status_is_not_not_started()
    {
        // Arrange
        $sut = $this->prepareTask();
        $sut->start();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The task must not have started.');

        // Act
        $sut->start();
    }

    #[Test]
    public function complete_a_task()
    {
        // Arrange
        $sut = $this->prepareTask();
        $sut->start();

        // Act
        $sut->complete();

        // Assert
        $this->assertEquals(TaskStatus::COMPLETED, $sut->getStatus());
    }

    #[Test]
    public function complete_a_task_when_status_is_not_in_progress()
    {
        // Arrange
        $sut = $this->prepareTask();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The task must not have completed.');

        // Act
        $sut->complete();
    }

    #[Test]
    public function complete_a_task_when_subtask_status_is_completed()
    {
        // Arrange
        $sut = $this->prepareTask();
        $sut->addSubtask(
            title: new SubtaskTitle(self::SUBTASK_TITLE),
            description: null
        );
        $subtask = $sut->getSubtasks()[0];
        $subtask->start();
        $subtask->complete();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The task must not complete.');

        // Act
        $sut->complete();
    }

    #[Test]
    public function reopen_a_task()
    {
        // Arrange
        $sut = $this->prepareTask();
        $sut->start();
        $sut->complete();

        // Act
        $sut->reopen();

        // Assert
        $this->assertEquals(TaskStatus::NOT_STARTED, $sut->getStatus());
    }

    #[Test]
    public function reopen_a_task_when_status_is_not_completed()
    {
        // Arrange
        $sut = $this->prepareTask();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The task cannot reopened.');

        // Act
        $sut->reopen();
    }

    #[Test]
    public function prioritize_a_task()
    {
        // Arrange
        $sut = $this->prepareTask();

        // Act
        $sut->prioritize(TaskPriority::CRITICAL);

        // Assert
        $this->assertEquals(TaskPriority::CRITICAL, $sut->getPriority());
    }

    #[Test]
    public function prioritize_a_task_when_status_is_completed()
    {
        // Arrange
        $sut = $this->prepareTask();
        $sut->start();
        $sut->complete();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The task cannot change the priority.');

        // Act
        $sut->prioritize(TaskPriority::CRITICAL);
    }

    #[Test]
    public function change_deadline_a_task()
    {
        // Arrange
        $sut = $this->prepareTask();

        // Act
        $sut->prioritize(TaskPriority::CRITICAL);

        // Assert
        $this->assertEquals(TaskPriority::CRITICAL, $sut->getPriority());
    }

    #[Test]
    public function change_deadline_a_task_when_status_is_completed()
    {
        // Arrange
        $sut = $this->prepareTask();
        $sut->start();
        $sut->complete();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The task cannot change the priority.');

        // Act
        $sut->prioritize(TaskPriority::CRITICAL);
    }
}
