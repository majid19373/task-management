<?php

namespace Tests\Unit\Domain\Subtask;

use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Subtask\Subtask;
use Src\Domain\Subtask\SubtaskDescription;
use Src\Domain\Subtask\SubtaskStatus;
use Src\Domain\Subtask\SubtaskTitle;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskTitle;

class SubtaskTest extends TestCase
{
    private const string TASK_TITLE = 'Test Task title';
    private const string SUBTASK_TITLE = 'Test Subtask title';
    private const string TASK_DESCRIPTION = 'Test Task Description';
    private const string SUBTASK_DESCRIPTION = 'Test Subtask Description';
    private const string BOARD_ID = 'board_id';
    private const string TASK_ID = 'task_id';
    private const string SUBTASK_ID = 'subtask_id';
    private const string PASSED_DATE = '2020-01-01 00:00:00';

    private function prepareTask(): Task
    {
        return new Task(
            id: self::TASK_ID,
            boardId: self::BOARD_ID,
            title: new TaskTitle(self::TASK_TITLE),
        );
    }

    private function prepareSubtask(): Subtask
    {
        return new Subtask(
            id: self::SUBTASK_ID,
            task: $this->prepareTask(),
            title: new SubtaskTitle(self::SUBTASK_TITLE),
        );
    }

    #[Test]
    public function create_a_task()
    {
        // Arrange
        $title = new SubtaskTitle(self::SUBTASK_TITLE);
        $description = new SubtaskDescription(self::SUBTASK_DESCRIPTION);
        $task = $this->prepareTask();

        // Act
        $subtask = new Subtask(
            id: self::SUBTASK_ID,
            task: $task,
            title: $title,
            description: $description,
        );

        // Assert
        $this->assertEquals($title, $subtask->getTitle());
        $this->assertEquals($description, $subtask->getDescription());
        $this->assertEquals(self::SUBTASK_ID, $subtask->getId());
        $this->assertEquals(SubtaskStatus::NOT_STARTED, $subtask->getStatus());
    }

    #[Test]
    public function create_a_subtask_with_optional_description()
    {
        // Arrange
        $title = new SubtaskTitle(self::SUBTASK_TITLE);
        $task = $this->prepareTask();

        // Act
        $subtask = new Subtask(
            id: self::SUBTASK_ID,
            task: $task,
            title: $title,
        );

        // Assert
        $this->assertNull($task->getDescription());
        $this->assertEquals($title, $subtask->getTitle());
        $this->assertEquals(self::SUBTASK_ID, $subtask->getId());
        $this->assertEquals(SubtaskStatus::NOT_STARTED, $subtask->getStatus());
    }

    #[Test]
    public function start_a_task()
    {
        // Arrange
        $sut = $this->prepareSubtask();

        // Act
        $sut->start();

        // Assert
        $this->assertEquals(SubtaskStatus::IN_PROGRESS, $sut->getStatus());
    }

    #[Test]
    public function start_a_subtask_when_status_is_not_not_started()
    {
        // Arrange
        $sut = $this->prepareSubtask();
        $sut->start();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The subtask must not have completed.');

        // Act
        $sut->start();
    }

    #[Test]
    public function complete_a_subtask()
    {
        // Arrange
        $sut = $this->prepareSubtask();
        $sut->start();

        // Act
        $sut->complete();

        // Assert
        $this->assertEquals(SubtaskStatus::COMPLETED, $sut->getStatus());
    }

    #[Test]
    public function complete_a_task_when_status_is_not_in_progress()
    {
        // Arrange
        $sut = $this->prepareSubtask();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The subtask must not have completed.');

        // Act
        $sut->complete();
    }

    #[Test]
    public function reopen_a_task()
    {
        // Arrange
        $sut = $this->prepareSubtask();
        $sut->start();
        $sut->complete();

        // Act
        $sut->reopen();

        // Assert
        $this->assertEquals(SubtaskStatus::NOT_STARTED, $sut->getStatus());
    }

    #[Test]
    public function reopen_a_task_when_status_is_not_completed()
    {
        // Arrange
        $sut = $this->prepareSubtask();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The subtask cannot reopened.');

        // Act
        $sut->reopen();
    }

}
