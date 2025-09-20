<?php

namespace Tests\Unit\Application\CommandHandlers\Task;

use DomainException;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Application\CommandHandlers\Task\PrioritizeTaskCommandHandler;
use Src\Application\CommandHandlers\Task\StartTaskCommandHandler;
use Src\Application\Commands\Task\PrioritizeTaskCommand;
use Src\Application\Commands\Task\StartTaskCommand;
use Src\Application\Repositories\TaskRepositoryInterface;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskPriority;
use Src\Domain\Task\TaskStatus;
use Src\Domain\Task\TaskTitle;
use Tests\Doubles\Repositories\FakeTaskRepository;

final class PrioritizeTaskCommandHandlerTest extends TestCase
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
    public function prioritize_a_task()
    {
        // Arrange
        $sut = new PrioritizeTaskCommandHandler($this->repository);
        $command = new PrioritizeTaskCommand($this->task->getId(), TaskPriority::MEDIUM->value);

        // Act
        $sut->handle($command);

        // Assert
        $task = $this->repository->getById($this->task->getId());
        $this->assertEquals(TaskPriority::MEDIUM, $task->getPriority());
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function prioritize_a_task_when_priority_is_wrong()
    {
        // Arrange
        $sut = new PrioritizeTaskCommandHandler($this->repository);
        $command = new PrioritizeTaskCommand($this->task->getId(), 'wrong_priority');

        // Expect
        $this->expectException(DomainException::class);

        // Act
        $sut->handle($command);
    }
}
