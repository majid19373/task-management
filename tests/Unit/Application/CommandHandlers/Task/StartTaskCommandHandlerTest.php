<?php

namespace Tests\Unit\Application\CommandHandlers\Task;

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

class StartTaskCommandHandlerTest extends TestCase
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
}
