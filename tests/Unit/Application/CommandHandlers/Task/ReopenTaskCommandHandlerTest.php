<?php

namespace Tests\Unit\Application\CommandHandlers\Task;

use DomainException;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Task\CompleteTaskCommandHandler;
use Src\Application\CommandHandlers\Task\ReopenTaskCommandHandler;
use Src\Application\CommandHandlers\Task\StartTaskCommandHandler;
use Src\Application\Commands\Task\CompleteTaskCommand;
use Src\Application\Commands\Task\ReopenTaskCommand;
use Src\Application\Commands\Task\StartTaskCommand;
use Src\Application\Repositories\TaskRepositoryInterface;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskStatus;
use Src\Domain\Task\TaskTitle;
use Tests\Doubles\Repositories\FakeTaskRepository;

final class ReopenTaskCommandHandlerTest extends TestCase
{
    private const string TASK_TITLE = 'Test Task Name';
    private const string BOARD_ID = 'board_id';
    private Task $task;
    private TaskRepositoryInterface $repository;
    public function setUp(): void
    {
        $this->repository = new FakeTaskRepository();
        $this->task = new Task(
            id: $this->repository->getNextIdentity(),
            boardId: self::BOARD_ID,
            title: new TaskTitle(self::TASK_TITLE),
        );
        $this->repository->store($this->task);
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function reopen_a_in_progress_task()
    {
        // Arrange
        $handler = new StartTaskCommandHandler($this->repository);
        $command = new StartTaskCommand($this->task->getId());
        $handler->handle($command);

        $handler = new CompleteTaskCommandHandler($this->repository);
        $command = new CompleteTaskCommand($this->task->getId());
        $handler->handle($command);

        $sut = new ReopenTaskCommandHandler($this->repository);
        $command = new ReopenTaskCommand($this->task->getId());

        // Act
        $sut->handle($command);

        // Assert
        $task = $this->repository->getById($this->task->getId());
        $this->assertEquals(TaskStatus::NOT_STARTED, $task->getStatus());
    }

    #[Test]
    public function task_completion_fails_if_status_is_not_in_progress()
    {
        // Arrange
        $sut = new CompleteTaskCommandHandler($this->repository);
        $command = new CompleteTaskCommand($this->task->getId());

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }
}
