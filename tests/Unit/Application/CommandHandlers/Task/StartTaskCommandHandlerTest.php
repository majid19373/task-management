<?php

namespace Tests\Unit\Application\CommandHandlers\Task;

use DomainException;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Task\StartTaskCommandHandler;
use Src\Application\Commands\Task\StartTaskCommand;
use Src\Application\Repositories\TaskRepositoryInterface;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskStatus;
use Src\Domain\Task\TaskTitle;
use Tests\Doubles\Repositories\FakeTaskRepository;

final class StartTaskCommandHandlerTest extends TestCase
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
    public function start_a_task()
    {
        // Arrange
        $sut = new StartTaskCommandHandler($this->repository);
        $command = new StartTaskCommand($this->task->getId());

        // Act
        $sut->handle($command);

        // Assert
        $task = $this->repository->getById($this->task->getId());
        $this->assertEquals(TaskStatus::IN_PROGRESS, $task->getStatus());
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function starting_task_fails_when_not_in_not_started_status()
    {
        // Arrange
        $sut = new StartTaskCommandHandler($this->repository);
        $command = new StartTaskCommand($this->task->getId());
        $sut->handle($command);

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }
}
