<?php

namespace Tests\Unit\Application\CommandHandlers\Task;

use DomainException;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Task\CompleteTaskCommandHandler;
use Src\Application\CommandHandlers\Task\StartTaskCommandHandler;
use Src\Application\Commands\Task\CompleteTaskCommand;
use Src\Application\Commands\Task\StartTaskCommand;
use Src\Application\Repositories\TaskRepositoryInterface;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskStatus;
use Src\Domain\Task\TaskTitle;
use Tests\Doubles\Repositories\FakeTaskRepository;

final class CompleteTaskCommandHandlerTest extends TestCase
{
    private Task $task;
    private TaskRepositoryInterface $repository;
    public function setUp(): void
    {
        $this->repository = new FakeTaskRepository();
        $this->task = new Task(
            id: $this->repository->getNextIdentity(),
            boardId: 'board_id',
            title: new TaskTitle('Test Board'),
        );
        $this->repository->store($this->task);
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function complete_a_in_progress_task()
    {
        // Arrange
        $handler = new StartTaskCommandHandler($this->repository);
        $command = new StartTaskCommand($this->task->getId());
        $handler->handle($command);

        $sut = new CompleteTaskCommandHandler($this->repository);
        $command = new CompleteTaskCommand($this->task->getId());

        // Act
        $sut->handle($command);

        // Assert
        $task = $this->repository->getById($this->task->getId());
        $this->assertEquals(TaskStatus::COMPLETED, $task->getStatus());
    }

    #[Test]
    public function reopening_task_fails_when_not_in_competed_status()
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
